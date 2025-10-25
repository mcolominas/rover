<?php

namespace Tests\Unit;

use App\Models\Planet;
use App\Models\Rover;
use App\Services\RoverService;
use App\Services\PlanetService;
use App\Services\ObstacleGenerator;
use App\Enums\Direction;
use App\Enums\Movement;
use App\Exceptions\InvalidPositionException;
use App\Exceptions\ObstacleDetectedException;
use App\Exceptions\ObstacleLimitExceededException;
use App\Exceptions\RoverAlreadyExistsException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class RoverServiceTest extends BaseTestCase
{
    use RefreshDatabase;

    protected RoverService $roverService;
    protected PlanetService $planetService;
    protected Planet $planetWithoutObstacles;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roverService = new RoverService();

        $obstacleGenerator = new ObstacleGenerator();
        $this->planetService = new PlanetService($obstacleGenerator);

        $this->planetWithoutObstacles = $this->planetService->createPlanet(5, 5, 0, 0);
    }

    public function test_it_launches_a_rover_successfully()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 2, 2, Direction::NORTH);

        $this->assertInstanceOf(Rover::class, $rover);
        $this->assertEquals(2, $rover->x);
        $this->assertEquals(2, $rover->y);
        $this->assertEquals(Direction::NORTH, $rover->direction);
    }

    public function test_it_fails_to_launch_rover_outside_boundaries()
    {
        $this->expectException(InvalidPositionException::class);

        $this->roverService->launchRover($this->planetWithoutObstacles, 10, 10, Direction::NORTH);
    }

    public function test_it_fails_to_launch_rover_on_obstacle()
    {
        $this->planetWithoutObstacles->obstacles()->create(['x' => 1, 'y' => 1]);

        $this->expectException(InvalidPositionException::class);

        $this->roverService->launchRover($this->planetWithoutObstacles, 1, 1, Direction::NORTH);
    }

    public function test_it_moves_forward_successfully()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 2, 2, Direction::NORTH);

        $result = $this->roverService->executeCommands($rover, Movement::FORWARD->value);

        $this->assertEquals(['x' => 2, 'y' => 3], $result['position']);
        $this->assertEquals('N', $result['direction']);
        $this->assertEquals(3, $rover->y);
        $this->assertEquals(2, $rover->x);
    }

    public function test_it_rotates_and_moves_after_turning_right()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 2, 2, Direction::NORTH);

        $this->roverService->executeCommands($rover, Movement::RIGHT->value);

        $this->assertEquals(3, $rover->x);
        $this->assertEquals(2, $rover->y);
        $this->assertEquals(Direction::EAST, $rover->direction);
    }

    public function test_it_rotates_and_moves_after_turning_left()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 2, 2, Direction::NORTH);

        $this->roverService->executeCommands($rover, Movement::LEFT->value);

        $this->assertEquals(1, $rover->x); // WEST
        $this->assertEquals(2, $rover->y);
        $this->assertEquals(Direction::WEST, $rover->direction);
    }

    public function test_it_detects_obstacle_and_throws_exception()
    {
        $this->planetWithoutObstacles->obstacles()->create(['x' => 2, 'y' => 1]);
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 2, 2, Direction::SOUTH);

        $this->expectException(ObstacleDetectedException::class);

        $this->roverService->executeCommands($rover, Movement::FORWARD->value);
    }

    public function test_it_executes_multiple_commands_correctly()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 0, 0, Direction::NORTH);

        $result = $this->roverService->executeCommands($rover, 'FFRFF');

        $this->assertEquals(['x' => 3, 'y' => 2], $result['position']);
        $this->assertEquals(Direction::EAST, $rover->direction);
        $this->assertEquals(3, $rover->x);
        $this->assertEquals(2, $rover->y);
    }

    public function test_it_wraps_around_the_map_when_moving_off_edges()
    {

        // NORTH wrap
        $planet1 = $this->planetService->createPlanet(5, 5, 0, 0);
        $rover = $this->roverService->launchRover($planet1, 2, 4, Direction::NORTH);
        $result = $this->roverService->executeCommands($rover, 'F');
        $this->assertEquals(['x' => 2, 'y' => 0], $result['position']);

        // WEST wrap
        $planet2 = $this->planetService->createPlanet(5, 5, 0, 0);
        $rover = $this->roverService->launchRover($planet2, 0, 2, Direction::WEST);
        $result = $this->roverService->executeCommands($rover, 'F');
        $this->assertEquals(['x' => 4, 'y' => 2], $result['position']);

        // SOUTH wrap
        $planet3 = $this->planetService->createPlanet(5, 5, 0, 0);
        $rover = $this->roverService->launchRover($planet3, 3, 0, Direction::SOUTH);
        $result = $this->roverService->executeCommands($rover, 'F');
        $this->assertEquals(['x' => 3, 'y' => 4], $result['position']);

        // EAST wrap
        $planet4 = $this->planetService->createPlanet(5, 5, 0, 0);
        $rover = $this->roverService->launchRover($planet4, 4, 1, Direction::EAST);
        $result = $this->roverService->executeCommands($rover, 'F');
        $this->assertEquals(['x' => 0, 'y' => 1], $result['position']);
    }

    public function test_it_stops_when_encountering_an_obstacle_mid_sequence()
    {
        $planet = $this->planetService->createPlanet(5, 5, 0, 0);
        $planet->obstacles()->create(['x' => 2, 'y' => 3]); // obstáculo

        $rover = $this->roverService->launchRover($planet, 2, 0, Direction::NORTH);

        $commands = 'FFFFF';

        try {
            $this->roverService->executeCommands($rover, $commands);
            $this->fail('Expected ObstacleDetectedException was not thrown.');
        } catch (ObstacleDetectedException $e) {
            $this->assertEquals(['x' => 2, 'y' => 2], ['x' => $rover->x, 'y' => $rover->y]);
            $this->assertEquals(2, $e->getX());
            $this->assertEquals(3, $e->getY());
        }
    }

    public function test_invalid_command_throws_exception()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 0, 0, Direction::NORTH);

        $this->expectException(InvalidPositionException::class);
        $this->roverService->executeCommands($rover, 'FX');
    }

    public function test_long_command_sequence_executes_correctly()
    {
        $rover = $this->roverService->launchRover($this->planetWithoutObstacles, 0, 0, Direction::NORTH);
        $sequence = 'FFRFLFFL';
        $result = $this->roverService->executeCommands($rover, $sequence);

        $this->assertEquals(['x' => 1, 'y' => 0], $result['position']);
        $this->assertEquals(Direction::WEST, $rover->direction);
    }

    public function test_cannot_launch_multiple_rovers_on_same_planet()
    {
        $this->roverService->launchRover($this->planetWithoutObstacles, 0, 0, Direction::NORTH);

        $this->expectException(RoverAlreadyExistsException::class);

        $this->roverService->launchRover($this->planetWithoutObstacles, 4, 4, Direction::SOUTH);
    }

    public function test_obstacle_limit_exception_from_generator()
    {
        $planet = Planet::factory()->create(['width' => 2, 'height' => 2]);
        $this->expectException(ObstacleLimitExceededException::class);

        $generator = new ObstacleGenerator();
        $generator->generate($planet, 5, 5);
    }
}
