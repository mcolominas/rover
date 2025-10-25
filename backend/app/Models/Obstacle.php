<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $planet_id
 * @property int $x
 * @property int $y
 * @property-read \App\Models\Planet $planet
 * @method static \Database\Factories\ObstacleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle wherePlanetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle whereX($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Obstacle whereY($value)
 * @mixin \Eloquent
 */
class Obstacle extends Model
{
    /** @use HasFactory<\Database\Factories\ObstacleFactory> */
    use HasFactory;

    protected $fillable = ['planet_id', 'x', 'y'];

    public $timestamps = false;

    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }
}
