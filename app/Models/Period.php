<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Period extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'teachers_periods');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'students_periods');
    }
}
