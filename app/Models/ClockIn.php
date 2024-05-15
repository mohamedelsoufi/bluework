<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     description="ClockIn model",
 *     type="object",
 *     title="ClockIn",
 *     @OA\Property(property="id", type="integer", description="ID"),
 *     @OA\Property(property="worker_id", type="integer", description="Worker ID"),
 *     @OA\Property(property="timestamp", type="string", format="date-time", description="Timestamp"),
 *     @OA\Property(property="latitude", type="number", format="float", description="Latitude"),
 *     @OA\Property(property="longitude", type="number", format="float", description="Longitude"),
 * )
 */

class ClockIn extends Model
{
    use HasFactory;

    protected $fillable = [
        'worker_id', 'timestamp', 'latitude', 'longitude'
    ];
}
