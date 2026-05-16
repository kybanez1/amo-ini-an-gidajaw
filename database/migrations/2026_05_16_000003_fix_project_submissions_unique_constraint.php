<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the old unique constraint (project_id, student_id)
        // and replace with (project_id, student_id, task_id)
        // so students can submit each task independently

        try {
            Schema::table('project_submissions', function (Blueprint $table) {
                $table->dropUnique(['project_id', 'student_id']);
            });
        } catch (\Exception $e) {
            // Constraint may not exist — ignore
        }

        // Add new unique constraint including task_id
        // Use a try/catch in case it already exists
        try {
            Schema::table('project_submissions', function (Blueprint $table) {
                $table->unique(['project_id', 'student_id', 'task_id'], 'project_student_task_unique');
            });
        } catch (\Exception $e) {
            // Already exists — ignore
        }
    }

    public function down(): void
    {
        try {
            Schema::table('project_submissions', function (Blueprint $table) {
                $table->dropUnique('project_student_task_unique');
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('project_submissions', function (Blueprint $table) {
                $table->unique(['project_id', 'student_id']);
            });
        } catch (\Exception $e) {}
    }
};
