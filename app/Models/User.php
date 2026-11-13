<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
        'role',
        'phone',
        'nisn_or_nip',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a teacher/tentor.
     */
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Determine if user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin() && $this->is_active;
    }

    /**
     * Student pairs where this user is the teacher.
     */
    public function teacherPairs(): HasMany
    {
        return $this->hasMany(StudentTeacherPair::class, 'teacher_id');
    }

    /**
     * Student pairs where this user is the student.
     */
    public function studentPairs(): HasMany
    {
        return $this->hasMany(StudentTeacherPair::class, 'student_id');
    }

    /**
     * Students assigned to this teacher.
     */
    public function myStudents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_teacher_pairs', 'teacher_id', 'student_id')
            ->withPivot('subject_id', 'is_active')
            ->withTimestamps();
    }

    /**
     * Teachers assigned to this student.
     */
    public function myTeachers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_teacher_pairs', 'student_id', 'teacher_id')
            ->withPivot('subject_id', 'is_active')
            ->withTimestamps();
    }

    /**
     * Availability slots defined by this teacher.
     */
    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class, 'teacher_id');
    }

    /**
     * Bookings made by this student.
     */
    public function studentBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    /**
     * Bookings received by this teacher.
     */
    public function teacherBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'teacher_id');
    }
}
