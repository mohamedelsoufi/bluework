<?php

namespace Tests\Unit;

use App\Http\Controllers\ClockInController;
use App\Models\ClockIn;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ClockInTest extends TestCase
{
    use RefreshDatabase;

    public function test_clockIn()
    {
        $controller = new ClockInController();
        $request = [
            'worker_id' => 123,
            'timestamp' => 1653544800,
            'latitude' => 30.049372457208833,
            'longitude' => 31.24030670015996,
        ];

        $response = $controller->clockIn(new \Illuminate\Http\Request([], $request));

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertEquals('Clock-in Created Successfully', $response->getData()->message);

    }

    public function testGetClockInsSuccess()
    {
        ClockIn::factory()->create(['worker_id' => 123]);
        $controller = new ClockInController();
        $request = new \Illuminate\Http\Request(['worker_id' => 123]);

        $response = $controller->getClockIns($request);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertGreaterThan(0, count($response->getData()));
    }

    public function testGetClockInsNoRecordsFound()
    {
        $controller = new ClockInController();
        $request = new \Illuminate\Http\Request(['worker_id' => 123]);

        $response = $controller->getClockIns($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertEquals('No records found', $response->getData()->error);
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
