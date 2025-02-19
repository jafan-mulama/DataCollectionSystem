<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * User role constants
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_LECTURER = 'lecturer';
    const ROLE_STUDENT = 'student';

    /**
     * Available roles
     */
    public static $roles = [
        self::ROLE_ADMIN => 'Administrator',
        self::ROLE_LECTURER => 'Lecturer',
        self::ROLE_STUDENT => 'Student',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get questionnaires created by the user
     */
    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
    }

    /**
     * Get responses submitted by the user
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is a lecturer
     */
    public function isLecturer(): bool
    {
        return $this->role === self::ROLE_LECTURER;
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return self::$roles[$this->role] ?? 'Unknown Role';
    }
}
