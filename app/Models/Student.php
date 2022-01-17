<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'password', 'name', 'grade'];

    /**
     * @return MorphOne
     */
    public function entity(): MorphOne
    {
        return $this->morphOne(Entity::class, 'model');
    }

    /**
     * @return HasMany
     */
    public function periods(): HasMany
    {
        return $this->hasMany(StudentsPeriod::class, 'student_id');
    }
}
