<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSubmission extends Model
{
    use HasFactory;

    protected $fillable = [

        'project_id',

        'task_id',

        'student_id',

        'file_path',

        'message',

        'content',

        'status',

        'score',

        'feedback',

        'submitted_at',

        'graded_at',
        'task_score',
    ];

    protected $casts = [

        'submitted_at' => 'datetime',

        'graded_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | PROJECT
    |--------------------------------------------------------------------------
    */
    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT
    |--------------------------------------------------------------------------
    */
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TASK
    |--------------------------------------------------------------------------
    */
    public function task(): BelongsTo
    {
        return $this->belongsTo(
            Task::class
        );
    }
}