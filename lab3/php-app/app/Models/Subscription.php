<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Subscription",
 *     description="A subscription to a particular service topic",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="service", type="string", example="example_service"),
 *     @OA\Property(property="topic", type="string", example="example_topic"),
 *     @OA\Property(property="payload", type="object", example={"key": "value"}),
 *     @OA\Property(property="expired_at", type="string", format="date-time", example="2023-12-31T23:59:59Z"),
 * )
 */
class Subscription extends Model
{
    use HasFactory;
    protected $fillable = ['service', 'topic', 'payload', 'expired_at', 'subscriber_id'];

	protected $casts = [
        'payload' => 'array',
    ];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
