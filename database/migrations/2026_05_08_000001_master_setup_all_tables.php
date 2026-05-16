<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Master safety migration — creates any missing tables and columns.
 * Each column added in its own Schema::table call to prevent MySQL batch errors.
 * Safe to run multiple times.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── SESSIONS ──────────────────────────────────────────────────────────
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // ── USERS extra columns ───────────────────────────────────────────────
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('student')->after('email');
            });
        }
        if (!Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('status')->default('active')->after('role');
            });
        }
        if (!Schema::hasColumn('users', 'student_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('student_id')->nullable()->after('status');
            });
        }
        if (!Schema::hasColumn('users', 'department')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('department')->nullable()->after('student_id');
            });
        }

        // ── GROUPS ────────────────────────────────────────────────────────────
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('active');
                $table->timestamps();
                $table->index('teacher_id');
            });
        }

        // ── GROUP_STUDENT pivot ───────────────────────────────────────────────
        if (!Schema::hasTable('group_student')) {
            Schema::create('group_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->tinyInteger('is_joined')->default(0);
                $table->timestamps();
                $table->unique(['group_id', 'student_id']);
            });
        }

        // ── PROJECTS ──────────────────────────────────────────────────────────
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
                $table->text('requirements')->nullable();
                $table->string('instruction_file')->nullable();
                $table->string('instruction_file_name')->nullable();
                $table->timestamp('instruction_file_uploaded_at')->nullable();
                $table->dateTime('start_date')->nullable();
                $table->dateTime('due_date')->nullable();
                $table->string('status')->default('draft');
                $table->integer('max_score')->default(100);
                $table->timestamps();
                $table->index('teacher_id');
                $table->index('group_id');
                $table->index('status');
            });
        } else {
            // Add missing columns to existing projects table (one per call)
            if (!Schema::hasColumn('projects', 'instruction_file')) {
                Schema::table('projects', function (Blueprint $table) {
                    $table->string('instruction_file')->nullable()->after('requirements');
                });
            }
            if (!Schema::hasColumn('projects', 'instruction_file_name')) {
                Schema::table('projects', function (Blueprint $table) {
                    $table->string('instruction_file_name')->nullable()->after('instruction_file');
                });
            }
            if (!Schema::hasColumn('projects', 'instruction_file_uploaded_at')) {
                Schema::table('projects', function (Blueprint $table) {
                    $table->timestamp('instruction_file_uploaded_at')->nullable()->after('instruction_file_name');
                });
            }
        }

        // ── PROJECT_STUDENT pivot ─────────────────────────────────────────────
        if (!Schema::hasTable('project_student')) {
            Schema::create('project_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->string('assignment_status')->default('assigned');
                $table->integer('score')->nullable();
                $table->text('feedback')->nullable();
                $table->dateTime('submitted_at')->nullable();
                $table->dateTime('graded_at')->nullable();
                $table->timestamps();
                $table->unique(['project_id', 'student_id']);
                $table->index('project_id');
                $table->index('student_id');
            });
        }

        // ── PROJECT_SUBMISSIONS ───────────────────────────────────────────────
        if (!Schema::hasTable('project_submissions')) {
            Schema::create('project_submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
                $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
                $table->text('content')->nullable();
                $table->string('file_path')->nullable();
                $table->string('status')->default('draft');
                $table->dateTime('submitted_at')->nullable();
                $table->timestamps();
                $table->index('project_id');
                $table->index('student_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
        Schema::dropIfExists('project_student');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('group_student');
        Schema::dropIfExists('groups');
    }
};
