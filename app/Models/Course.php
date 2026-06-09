<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'code',
        'description',
        'credit_hours'
    ];

    // Relationships
    // A course can have many enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // A course can belong to many users
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments');
    }
}
