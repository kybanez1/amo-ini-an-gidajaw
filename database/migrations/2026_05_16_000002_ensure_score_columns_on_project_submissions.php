<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {

            if (!Schema::hasColumn('project_submissions', 'score')) {
                $table->decimal('score', 8, 2)->nullable()->after('status');
            }

            if (!Schema::hasColumn('project_submissions', 'feedback')) {
                $table->text('feedback')->nullable()->after('score');
            }

            if (!Schema::hasColumn('project_submissions', 'graded_at')) {
                $table->dateTime('graded_at')->nullable()->after('feedback');
            }

            if (!Schema::hasColumn('project_submissions', 'message')) {
                $table->text('message')->nullable()->after('content');
            }

            if (!Schema::hasColumn('project_submissions', 'task_id')) {
                $table->unsignedBigInteger('task_id')->nullable()->after('student_id');
            }

            if (!Schema::hasColumn('project_submissions', 'task_score')) {
                $table->decimal('task_score', 8, 2)->nullable()->after('score');
            }

        });
    }

    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            foreach (['score', 'feedback', 'graded_at', 'message', 'task_id', 'task_score'] as $col) {
                if (Schema::hasColumn('project_submissions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
