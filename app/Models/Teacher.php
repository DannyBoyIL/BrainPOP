<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'password', 'name', 'email'];

    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, 'teachers_periods');
    }

    /**
     * @return MorphOne
     */
    public function entity(): MorphOne
    {
        return $this->morphOne(Entity::class, 'model');
    }
}
