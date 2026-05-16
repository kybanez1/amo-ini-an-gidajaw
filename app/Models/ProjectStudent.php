<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectStudent extends Model
{
    use HasFactory;

    protected $table = 'project_student';

    protected $fillable = [
        'project_id',
        'student_id',
        'assignment_status',
        'score',
        'feedback',
        'submitted_at',
        'graded_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Get the student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Mark as submitted
     */
    public function markAsSubmitted(): void
    {
        $this->update([
            'assignment_status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    /**
     * Mark as graded
     */
    public function markAsGraded($score, $feedback = null): void
    {
        $this->update([
            'assignment_status' => 'graded',
            'score' => $score,
            'feedback' => $feedback,
            'graded_at' => now(),
        ]);
    }

    /**
     * Check if submitted
     */
    public function isSubmitted(): bool
    {
        return in_array($this->assignment_status, ['submitted', 'graded', 'returned']);
    }

    /**
     * Check if graded
     */
    public function isGraded(): bool
    {
        return in_array($this->assignment_status, ['graded', 'returned']);
    }

    /**
     * Get score percentage
     */
    public function getScorePercentage(): ?float
    {
        if ($this->score === null || !$this->project) {
            return null;
        }
        return ($this->score / $this->project->max_score) * 100;
    }
}
