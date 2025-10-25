<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property int $width
 * @property int $height
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Obstacle> $obstacles
 * @property-read int|null $obstacles_count
 * @property-read \App\Models\Rover|null $rover
 * @method static \Database\Factories\PlanetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereWidth($value)
 * @mixin \Eloquent
 */
class Planet extends Model
{
    /** @use HasFactory<\Database\Factories\PlanetFactory> */
    use HasFactory;

    protected $fillable = ['width', 'height'];

    public function obstacles()
    {
        return $this->hasMany(Obstacle::class);
    }

    public function rover()
    {
        return $this->hasOne(Rover::class);
    }

    public function hasObstacle(int $x, int $y): bool
    {
        return Cache::remember("planet_{$this->id}_obstacle_{$x}_{$y}", 60, function () use ($x, $y) {
            if ($this->relationLoaded('obstacles')) {
                return $this->obstacles->contains(fn($ob) => $ob->x === $x && $ob->y === $y);
            }

            return $this->obstacles()->where('x', $x)->where('y', $y)->exists();
        });
    }
}
