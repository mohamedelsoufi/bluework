<?php

use App\Models\Permission;

const LOCATION_LATITUDE = 30.049372457208833; // Example latitude
const LOCATION_LONGITUDE = 31.24030670015996; // Example longitude
const MAX_DISTANCE_KM = 2; // 2 kilometers

function successResponse($data = [], $message = "", $status = 200)
{
    return response()->json(
        [
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ],
        $status
    );
}

function failureResponse($data = [], $message = "", $status = 400)
{
    return response()->json(
        [
            "status" => $status,
            "message" => $message,
            "data" => $data,
        ],
        $status
    );
}

