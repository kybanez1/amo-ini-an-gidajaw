<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Section extends Model
{
    protected $fillable = [
        'teacher_id',
        'name',
        'code',
        'description',
        'school_year',
        'semester',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | BOOT — auto-generate unique section code
    |--------------------------------------------------------------------------
    */
    protected static function booted(): void
    {
        static::creating(function (Section $section) {
            if (empty($section->code)) {
                $section->code = self::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    public function regenerateCode(): string
    {
        $code = self::generateCode();
        $this->update(['code' => $code]);
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

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'section_student',
            'section_id',
            'student_id'
        )->withPivot('joined_at')->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function hasStudent(int $studentId): bool
    {
        return $this->students()->where('users.id', $studentId)->exists();
    }
}
