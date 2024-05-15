<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClockInRequest;
use App\Http\Requests\GetClockInsRequest;
use App\Models\ClockIn;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClockInController extends Controller
{
    /**
     * @OA\Post(
     *     path="/worker/clock-in",
     *     summary="Clock in a worker",
     *     tags={"Worker"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="worker_id", type="integer", example=123),
     *             @OA\Property(property="timestamp", type="integer", example=1653544800),
     *             @OA\Property(property="latitude", type="number", format="float", example=40.748817),
     *             @OA\Property(property="longitude", type="number", format="float", example=-73.985428)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Clock-in successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Clock-in successful.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Location is not within the allowed range.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Location is not within the allowed range.")
     *         )
     *     )
     * )
     */
    public function clockIn(Request $request)
    {
        try {
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            if (!$this->isWithinDistance($latitude, $longitude)) {
                return failureResponse(['Location is not within the allowed range'], __("Something went wrong"), 403);
            }

            $clockIn = ClockIn::create([
                'worker_id' => $request->worker_id,
                'timestamp' => Carbon::createFromTimestamp($request->timestamp),
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);

            return successResponse($clockIn, __("Clock-in Created Successfully"), 200);
        }catch (\Exception $e){
            return failureResponse([], __("Something went wrong"), 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/worker/clock-ins",
     *     summary="Get worker clock-ins",
     *     tags={"Worker"},
     *     @OA\Parameter(
     *         name="worker_id",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of clock-ins",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ClockIn")
     *         )
     *     )
     * )
     */
    public function getClockIns(GetClockInsRequest $request)
    {
        try {
            $clockIns = ClockIn::where('worker_id', $request->worker_id)->get();

            if (!$clockIns->count() > 0) {
                return failureResponse([], __("No records found"), 400);
            }

            return successResponse($clockIns, __("Success"), 200);
        }catch (\Exception $e){
            return failureResponse([], __("Something went wrong"), 400);
        }
    }

    /*
     * @param decimal $latitude
     * @param decimal $longitude
     * @return calculate the distance
     */
    public function isWithinDistance($latitude, $longitude)
    {
        $theta = LOCATION_LONGITUDE - $longitude;
        $dist = sin(deg2rad(LOCATION_LATITUDE)) * sin(deg2rad($latitude)) + cos(deg2rad(LOCATION_LATITUDE)) * cos(deg2rad($latitude)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $km = $dist * 60 * 1.1515 * 1.609344;

        return $km <= MAX_DISTANCE_KM;
    }
}
