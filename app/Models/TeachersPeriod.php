<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TeachersPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['period_id', 'teacher_id'];

    public $timestamps = false;

    /**
     * @return BelongsTo
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }
}
