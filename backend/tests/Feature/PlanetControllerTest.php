<?php

namespace Tests\Feature;

use App\Exceptions\ObstacleLimitExceededException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class PlanetControllerTest extends BaseTestCase
{
    use RefreshDatabase;

    public const HTTP_CODE_OK = 200;
    public const HTTP_CODE_RESOURCE_CREATED = 201;
    public const HTTP_CODE_VALIDATION_ERROR = 422;

    public function test_can_create_planet()
    {
        $response = $this->postJson('/api/planet');

        $response->assertStatus(self::HTTP_CODE_RESOURCE_CREATED)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'width',
                    'height',
                ]
            ]);

        $this->assertDatabaseCount('planets', 1);
    }

    public function test_planet_creation_respects_obstacle_limits()
    {
        // Set configuration for create a small planet with many obstacles
        config(['planet.width' => 2, 'planet.height' => 2, 'planet.max_obstacles' => 5]);

        $response = $this->postJson('/api/planet');

        $response->assertStatus(ObstacleLimitExceededException::HTTP_STATUS_CODE)
            ->assertJsonStructure(['message']);

        $this->assertEquals(1004, $response->json('error'));
    }
}
