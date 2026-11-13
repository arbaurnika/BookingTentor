<?php

namespace App\Models;

use Database\Factories\SubjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    /** @use HasFactory<SubjectFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * Student teacher pairs for this subject.
     */
    public function studentTeacherPairs(): HasMany
    {
        return $this->hasMany(StudentTeacherPair::class);
    }

    /**
     * Bookings for this subject.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
