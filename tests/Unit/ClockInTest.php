<?php

namespace Tests\Unit;

use App\Http\Controllers\ClockInController;
use App\Http\Requests\ClockInRequest;
use App\Http\Requests\GetClockInsRequest;
use App\Models\ClockIn;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;
use Mockery;

class ClockInTest extends TestCase
{
    use RefreshDatabase;

    public function testClockInWithinDistance()
    {
        // Arrange
        $request = new ClockInRequest([
            'worker_id' => 1,
            'timestamp' => Carbon::now()->timestamp,
            'latitude' => LOCATION_LATITUDE,
            'longitude' => LOCATION_LONGITUDE,
        ]);

        $controller = new ClockInController();

        // Act
        $response = $controller->clockIn($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = (array) $response->getData();
        $this->assertArrayHasKey('data', $responseData);
        $this->assertArrayHasKey('message', $responseData);
    }

    public function testGetClockInsSuccess()
    {
        // Arrange
        $request = new GetClockInsRequest([
            'worker_id' => 1,
        ]);

        $clockIn1 = ClockIn::create([
            'worker_id' => 1,
            'timestamp' => Carbon::now()->subDays(1),
            'latitude' => 40.730610,
            'longitude' => -73.935242,
        ]);

        $clockIn2 = ClockIn::create([
            'worker_id' => 1,
            'timestamp' => Carbon::now(),
            'latitude' => 40.730610,
            'longitude' => -73.935242,
        ]);

        $controller = new ClockInController();

        // Act
        $response = $controller->getClockIns($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = (array) $response->getData();
        $this->assertArrayHasKey('data', $responseData);
        $this->assertCount(2, $responseData['data']);
    }

    public function testGetClockInsNoRecordsFound()
    {
        // Arrange
        $request = new GetClockInsRequest([
            'worker_id' => 2,
        ]);

        $controller = new ClockInController();

        // Act
        $response = $controller->getClockIns($request);

        // Assert
        $this->assertEquals(400, $response->getStatusCode());
    }


    public function testIsWithinDistance()
    {
        $controller = new ClockInController();

        // Assuming LOCATION_LATITUDE, LOCATION_LONGITUDE, and MAX_DISTANCE_KM are defined
        $withinDistance = $controller->isWithinDistance(LOCATION_LATITUDE, LOCATION_LONGITUDE);
        $this->assertTrue($withinDistance);

        $outOfDistance = $controller->isWithinDistance(50.0, -73.985428);
        $this->assertFalse($outOfDistance);
    }
}
