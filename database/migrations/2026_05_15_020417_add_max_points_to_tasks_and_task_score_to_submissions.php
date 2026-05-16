<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add max_points to tasks
        if (!Schema::hasColumn('tasks', 'max_points')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->unsignedInteger('max_points')
                      ->default(100)
                      ->after('score');
            });
        }

        // Add task_score to project_submissions
        if (!Schema::hasColumn('project_submissions', 'task_score')) {
            Schema::table('project_submissions', function (Blueprint $table) {
                $table->decimal('task_score', 8, 2)
                      ->nullable()
                      ->after('grade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tasks', 'max_points')) {
            Schema::table('tasks', function (Blueprint $table) {
                $table->dropColumn('max_points');
            });
        }

        if (Schema::hasColumn('project_submissions', 'task_score')) {
            Schema::table('project_submissions', function (Blueprint $table) {
                $table->dropColumn('task_score');
            });
        }
    }
};