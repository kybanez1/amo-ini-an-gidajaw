<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'name',
        'description',
        'teacher_id',
        'section_id',
        'status',
        'join_code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT — auto-generate join_code on creation
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function (Group $group) {
            if (empty($group->join_code)) {
                $group->join_code = self::generateUniqueCode();
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE UNIQUE 6-CHAR CODE
    |--------------------------------------------------------------------------
    */
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('join_code', $code)->exists());

        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | REGENERATE CODE
    |--------------------------------------------------------------------------
    */
    public function regenerateCode(): string
    {
        $code = self::generateUniqueCode();
        $this->update(['join_code' => $code]);
        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * ALL students — teacher-side, no pivot filter
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'group_student',
            'group_id',
            'student_id'
        )->withPivot('is_joined')->withTimestamps();
    }

    /**
     * Students who joined via code (is_joined = 1)
     */
    public function joinedStudents(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'group_student',
            'group_id',
            'student_id'
        )->withPivot('is_joined')
         ->wherePivot('is_joined', 1)
         ->withTimestamps();
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'group_id');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function addStudent(User $student): void
    {
        if (!$this->students()->where('users.id', $student->id)->exists()) {
            // Teacher-added: is_joined=0 — student must still enter code
            $this->students()->attach($student->id, ['is_joined' => 0]);
        }
    }

    public function removeStudent(User $student): void
    {
        $this->students()->detach($student->id);
    }

    public function getStudentCount(): int
    {
        return $this->students()->count();
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasStudent($studentId): bool
    {
        return $this->students()->where('users.id', $studentId)->exists();
    }
}