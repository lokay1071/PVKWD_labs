<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Subscriber",
 *     description="A subscriber to a service",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="email", type="string", format="email", example="example@example.com"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 * )
 */
class Subscriber extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'name'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
