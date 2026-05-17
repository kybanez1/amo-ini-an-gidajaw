<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'student_id',
        'department',
        'teacher_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ===== TEACHER RELATIONSHIPS =====
    
    /**
     * Get all groups created by this teacher
     */
    public function groups()
{
    return $this->hasMany(Group::class, 'teacher_id');
}

    /**
     * Get all projects created by this teacher
     */
    public function projects()
{
    return $this->hasMany(Project::class, 'teacher_id');
}


    // ===== STUDENT RELATIONSHIPS =====
    
    /**
     * Get all groups this student belongs to
     */
    /**
     * ALL groups this user belongs to (teacher-side use, no pivot filter)
     */
    public function studentGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'group_student',
            'student_id',
            'group_id'
        )->withTimestamps();
    }

    /**
     * Groups the student has actively joined via code (is_joined = 1)
     * Used ONLY on student-facing dashboard and views
     */
    public function joinedGroups(): BelongsToMany
    {
        return $this->belongsToMany(
            Group::class,
            'group_student',
            'student_id',
            'group_id'
        )->withPivot('is_joined')
         ->wherePivot('is_joined', 1)
         ->withTimestamps();
    }

    /**
     * Get all projects assigned to this student
     */
    public function assignedProjects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_student', 'student_id', 'project_id')
                    ->withPivot('assignment_status', 'score', 'feedback', 'submitted_at', 'graded_at')
                    ->withTimestamps();
    }

    /**
     * Get all submissions from this student
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectSubmission::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | BOOT — auto-generate teacher_code for new teachers
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if ($user->role === 'teacher' && empty($user->teacher_code)) {
                $user->teacher_code = self::generateTeacherCode();
            }
        });
    }

    public static function generateTeacherCode(): string
    {
        do {
            $code = strtoupper(\Illuminate\Support\Str::random(6));
        } while (self::where('teacher_code', $code)->exists());

        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | TEACHER — students who entered this teacher's code
    |--------------------------------------------------------------------------
    */
    public function myStudents(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'teacher_student',
            'teacher_id',
            'student_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT — teachers this student has registered under
    |--------------------------------------------------------------------------
    */
    public function myTeachers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'teacher_student',
            'student_id',
            'teacher_id'
        )->withTimestamps();
    }

    /**
     * Check if user is a teacher
     */
    /*
    |--------------------------------------------------------------------------
    | SECTION RELATIONSHIPS
    |--------------------------------------------------------------------------
    */
    public function sections(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

    public function joinedSections(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            Section::class,
            'section_student',
            'student_id',
            'section_id'
        )->withPivot('joined_at')->withTimestamps();
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}