<?php

namespace App\Models;

use App\Models\Task;
use App\Models\Group;
use App\Models\User;
use App\Models\ProjectSubmission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'title',
        'description',
        'requirements',
        'instruction_file',
        'instruction_file_name',
        'instruction_file_uploaded_at',
        'instruction_link',
        'group_id',
        'teacher_id',
        'start_date',
        'due_date',
        'status',
        'max_score',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'start_date'                   => 'datetime',
        'due_date'                     => 'datetime',
        'max_score'                    => 'integer',
        'instruction_file_uploaded_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Group Relationship
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Teacher Relationship
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Students Assigned
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
                User::class,
                'project_student',
                'project_id',
                'student_id'
            )
            ->withPivot([
                'assignment_status',
                'score',
                'feedback',
                'submitted_at',
                'graded_at'
            ])
            ->withTimestamps();
    }

    /**
     * Project Submissions
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ProjectSubmission::class);
    }

    /**
     * Project Tasks
     *
     * IMPORTANT:
     * This uses project_id automatically.
     * Your tasks table MUST contain:
     *
     * - id
     * - project_id
     * - title
     * - description
     * - due_date
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    /**
     * Assignments
     */
    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(
                User::class,
                'project_student',
                'project_id',
                'student_id'
            )
            ->withPivot([
                'assignment_status',
                'submitted_at',
                'score',
                'feedback'
            ])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGNMENT LOGIC
    |--------------------------------------------------------------------------
    */

    /**
     * Assign Project to Student
     */
    public function assignToStudent(
        User $student,
        $status = 'assigned'
    ): void {

        $exists = $this->students()
            ->where('users.id', $student->id)
            ->exists();

        if (!$exists) {

            $this->students()->attach($student->id, [
                'assignment_status' => $status,
            ]);
        }
    }

    /**
     * Assign Project to Group
     */
    public function assignToGroup(Group $group): void
    {
        foreach ($group->students as $student) {

            $this->assignToStudent($student);

        }
    }

    /*
    |--------------------------------------------------------------------------
    | TASK HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Create Task
     */
    public function createTask(array $data): Task
    {
        return $this->tasks()->create([

            'title' => $data['title'] ?? 'Untitled Task',

            'description' => $data['description'] ?? null,

            'due_date' => $data['due_date'] ?? null,

        ]);
    }

    /**
     * Check if project has tasks
     */
    public function hasTasks(): bool
    {
        return $this->tasks()->exists();
    }

    /*
    |--------------------------------------------------------------------------
    | STATS
    |--------------------------------------------------------------------------
    */

    /**
     * Submitted Count
     */
    public function getSubmittedCount(): int
    {
        return $this->assignments()
            ->where('assignment_status', 'submitted')
            ->count();
    }

    /**
     * Graded Count
     */
    public function getGradedCount(): int
    {
        return $this->assignments()
            ->where('assignment_status', 'graded')
            ->count();
    }

    /**
     * Tasks Count
     */
    public function getTasksCount(): int
    {
        return $this->tasks()->count();
    }

    /*
    |--------------------------------------------------------------------------
    | STATUS HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Is Published
     */
    public function isPublished(): bool
    {
        return in_array($this->status, [
            'published',
            'ongoing',
            'completed'
        ]);
    }

    /**
     * Is Active
     */
    public function isActive(): bool
    {
        return in_array($this->status, [
                'published',
                'ongoing'
            ])
            && $this->start_date
            && $this->due_date
            && now()->between(
                $this->start_date,
                $this->due_date
            );
    }

    /**
     * Is Overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date
            && now()->isAfter($this->due_date);
    }

    /**
     * Days Until Due
     */
    public function getDaysUntilDue(): int
    {
        return $this->due_date
            ? now()->diffInDays($this->due_date, false)
            : 0;
    }
}