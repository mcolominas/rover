<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Planet;
use App\Enums\Direction;
use App\Enums\Movement;
use App\Exceptions\InvalidPositionException;
use App\Exceptions\ObstacleDetectedException;
use App\Exceptions\RoverAlreadyExistsException;
use App\Models\Rover;
use App\Services\ObstacleGenerator;
use App\Services\PlanetService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class RoverControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public const HTTP_CODE_OK = 200;
    public const HTTP_CODE_RESOURCE_CREATED = 201;
    public const HTTP_CODE_VALIDATION_ERROR = 422;

    protected PlanetService $planetService;
    protected Planet $planetWithoutObstacles;

    protected function setUp(): void
    {
        parent::setUp();

        $obstacleGenerator = new ObstacleGenerator();
        $this->planetService = new PlanetService($obstacleGenerator);

        $this->planetWithoutObstacles = $this->planetService->createPlanet(5, 5, 0, 0);
    }

    /** ------------------------------
     *  LAUNCH VALIDATIONS
     *  ------------------------------ */
    public function test_it_fails_when_required_fields_are_missing()
    {
        $response = $this->postJson("/api/rovers/launch", []);

        $response->assertStatus(self::HTTP_CODE_VALIDATION_ERROR)
            ->assertJsonStructure([
                'error',
                'message',
                'errors' => ['planet_id', 'x', 'y', 'direction']
            ]);
    }

    public function test_it_fails_when_direction_is_invalid()
    {
        $response = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 1,
            'y' => 1,
            'direction' => 'Z'
        ]);

        $response->assertStatus(self::HTTP_CODE_VALIDATION_ERROR)
            ->assertJsonStructure([
                'errors' => ['direction']
            ]);
    }

    public function test_it_fails_when_coordinates_are_out_of_bounds()
    {
        $response = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 10,
            'y' => 10,
            'direction' => 'N'
        ]);

        $response->assertStatus(self::HTTP_CODE_VALIDATION_ERROR)
            ->assertJsonStructure([
                'errors' => ['x', 'y']
            ]);
    }

    public function test_it_passes_with_valid_data()
    {
        $response = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 2,
            'y' => 3,
            'direction' => 'E'
        ]);

        $response->assertStatus(self::HTTP_CODE_RESOURCE_CREATED);
    }

    /** ------------------------------
     *  ROVER LAUNCH
     *  ------------------------------ */
    public function test_can_launch_rover()
    {
        $response = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 0,
            'y' => 0,
            'direction' => Direction::NORTH->value,
        ]);

        $response->assertStatus(self::HTTP_CODE_RESOURCE_CREATED)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'planet_id',
                    'position' => ['x', 'y'],
                    'direction',
                ]
            ]);

        $this->assertDatabaseHas('rovers', ['planet_id' => $this->planetWithoutObstacles->id, 'x' => 0, 'y' => 0]);
    }

    public function test_cannot_launch_rover_on_obstacle()
    {
        $this->planetWithoutObstacles->obstacles()->create(['x' => 0, 'y' => 0]);

        $response = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 0,
            'y' => 0,
            'direction' => Direction::NORTH->value,
        ]);

        $this->assertEquals(1001, $response->json('error'));

        $response->assertStatus(InvalidPositionException::HTTP_STATUS_CODE)
            ->assertJsonStructure(['message']);
    }

    public function test_it_throws_rover_already_exists_exception()
    {
        $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 0,
            'y' => 0,
            'direction' => Direction::NORTH->value,
        ]);

        $roverResponse2 = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 1,
            'y' => 0,
            'direction' => Direction::NORTH->value,
        ]);

        $roverResponse2->assertStatus(RoverAlreadyExistsException::HTTP_STATUS_CODE)
            ->assertJson([
                'error' => 1005,
                'message' => 'A rover already exists on this planet.',
            ]);
    }

    /** ------------------------------
     *  EXECUTE COMMANDS VALIDATIONS
     *  ------------------------------ */
    public function test_it_fails_when_commands_is_missing()
    {
        $rover = Rover::factory()->create(['planet_id' => $this->planetWithoutObstacles->id]);

        $response = $this->postJson("/api/rovers/{$rover->id}/commands", []);

        $response->assertStatus(self::HTTP_CODE_VALIDATION_ERROR)
            ->assertJsonStructure([
                'error',
                'message',
                'errors' => ['commands']
            ]);
    }

    public function test_it_fails_when_commands_is_empty()
    {
        $rover = Rover::factory()->create(['planet_id' => $this->planetWithoutObstacles->id]);

        $response = $this->postJson("/api/rovers/{$rover->id}/commands", [
            'commands' => ''
        ]);

        $response->assertStatus(self::HTTP_CODE_VALIDATION_ERROR)
            ->assertJsonStructure([
                'error',
                'message',
                'errors' => ['commands']
            ]);
    }

    public function test_it_fails_when_commands_contains_invalid_characters()
    {
        $rover = Rover::factory()->create(['planet_id' => $this->planetWithoutObstacles->id]);

        $response = $this->postJson("/api/rovers/{$rover->id}/commands", [
            'commands' => 'FX1L'
        ]);

        $response->assertStatus(self::HTTP_CODE_VALIDATION_ERROR)
            ->assertJsonFragment([
                'commands' => ['Commands may only contain F, L or R characters.']
            ]);
    }

    /** ------------------------------
     *  EXECUTE COMMANDS
     *  ------------------------------ */
    public function test_it_passes_with_valid_commands()
    {
        $rover = Rover::factory()->create(['planet_id' => $this->planetWithoutObstacles->id]);

        $response = $this->postJson("/api/rovers/{$rover->id}/commands", [
            'commands' => 'FLR'
        ]);

        $response->assertStatus(self::HTTP_CODE_OK);
    }

    public function test_execute_commands_returns_path_and_position()
    {
        $roverResponse = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 0,
            'y' => 0,
            'direction' => Direction::NORTH->value,
        ]);

        $roverId = $roverResponse->json('data.id');

        $command = 'FFRFFFFFFFFFFFF';

        $response = $this->postJson("/api/rovers/{$roverId}/commands", [
            'commands' => $command,
        ]);

        $response->assertStatus(self::HTTP_CODE_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'position' => ['x', 'y'],
                    'direction',
                    'path' => [
                        '*' => ['position' => ['x', 'y'], 'direction', 'movement']
                    ],
                ]
            ]);

        $this->assertCount(strlen($command), $response->json('data.path'));
    }

    public function test_execute_commands_stops_on_obstacle()
    {
        $this->planetWithoutObstacles->obstacles()->create(['x' => 0, 'y' => 3]);

        $roverResponse = $this->postJson("/api/rovers/launch", [
            'planet_id' => $this->planetWithoutObstacles->id,
            'x' => 0,
            'y' => 0,
            'direction' => Direction::NORTH->value,
        ]);

        $roverId = $roverResponse->json('data.id');

        $response = $this->postJson("/api/rovers/{$roverId}/commands", [
            'commands' => 'FFFFFF',
        ]);

        $response->assertStatus(ObstacleDetectedException::HTTP_STATUS_CODE)
            ->assertJsonStructure([
                'message',
                'path'
            ]);

        $this->assertEquals(1002, $response->json('error'));

        $this->assertEquals([
            ['position' => ['x' => 0, 'y' => 1], 'direction' => Direction::NORTH->value, 'movement' => Movement::FORWARD->value],
            ['position' => ['x' => 0, 'y' => 2], 'direction' => Direction::NORTH->value, 'movement' => Movement::FORWARD->value],
        ], $response->json('path'));
    }
}
