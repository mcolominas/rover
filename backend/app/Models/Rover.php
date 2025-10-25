<?php

namespace App\Models;

use App\Enums\Direction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $planet_id
 * @property int $x
 * @property int $y
 * @property Direction $direction
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Planet $planet
 * @method static \Database\Factories\RoverFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rover whereY($value)
 * @mixin \Eloquent
 */
class Rover extends Model
{
    /** @use HasFactory<\Database\Factories\RoverFactory> */
    use HasFactory;

    protected $fillable = [
        'planet_id',
        'x',
        'y',
        'direction',
    ];

    protected $casts = [
        'direction' => Direction::class,
    ];

    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }
}
