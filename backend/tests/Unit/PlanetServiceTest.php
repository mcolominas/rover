<?php

namespace Tests\Unit;

use App\Models\Planet;
use App\Services\PlanetService;
use App\Services\ObstacleGenerator;
use App\Exceptions\ObstacleLimitExceededException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use InvalidArgumentException;

class PlanetServiceTest extends BaseTestCase
{
    use RefreshDatabase;

    protected PlanetService $planetService;

    protected function setUp(): void
    {
        parent::setUp();

        $obstacleGenerator = new ObstacleGenerator();
        $this->planetService = new PlanetService($obstacleGenerator);
    }

    public function test_it_creates_a_planet_with_random_obstacles()
    {
        $planet = $this->planetService->createPlanet(
            width: 5,
            height: 5,
            minObstacles: 1,
            maxObstacles: 3
        );

        $this->assertInstanceOf(Planet::class, $planet);

        $count = $planet->obstacles()->count();
        $this->assertGreaterThanOrEqual(1, $count);
        $this->assertLessThanOrEqual(3, $count);
    }

    public function test_it_throws_exception_if_obstacle_limit_exceeds_planet_size()
    {
        $planet = Planet::factory()->create(['width' => 2, 'height' => 2]);

        $this->expectException(ObstacleLimitExceededException::class);

        // Llamamos directamente al generador de obstáculos
        $obstacleGenerator = new ObstacleGenerator();
        $obstacleGenerator->generate($planet, 5, 5);
    }

    public function test_it_detects_obstacles_correctly()
    {
        $planet = Planet::factory()->create(['width' => 5, 'height' => 5]);

        // Creamos un obstáculo específico
        $planet->obstacles()->create(['x' => 1, 'y' => 1]);

        $this->assertTrue($planet->hasObstacle(1, 1));
        $this->assertFalse($planet->hasObstacle(2, 2));
    }

    public function test_creates_planet_with_zero_obstacles_if_min_max_are_zero()
    {
        $planet = $this->planetService->createPlanet(5, 5, 0, 0);
        $this->assertEquals(0, $planet->obstacles()->count());
    }

    /** -------------------------------------
     *  OBSTACLE GENERATOR
     *  ------------------------------------- */
    public function test_obstacle_limit_exception_from_generator()
    {
        $planet = Planet::factory()->create(['width' => 2, 'height' => 2]);
        $this->expectException(ObstacleLimitExceededException::class);

        $generator = new ObstacleGenerator();
        $generator->generate($planet, 5, 5);
    }

    public function test_it_throws_exception_when_min_is_greater_than_max()
    {
        $planet = Planet::factory()->create(['width' => 5, 'height' => 5]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minimum value cannot be greater than the maximum value.');

        $obstacleGenerator = new ObstacleGenerator();
        $obstacleGenerator->generate($planet, 5, 2); // min > max
    }
}
