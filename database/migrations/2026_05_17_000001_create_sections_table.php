<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sections')) {
            Schema::create('sections', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
                $table->string('name');              // e.g. BSICT-2A1
                $table->string('code', 8)->unique(); // e.g. SEC-A1B2
                $table->string('description')->nullable();
                $table->string('school_year')->nullable(); // e.g. 2025-2026
                $table->string('semester')->nullable();    // e.g. 1st Semester
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('section_student')) {
            Schema::create('section_student', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('joined_at')->nullable();
                $table->timestamps();
                $table->unique(['section_id', 'student_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('section_student');
        Schema::dropIfExists('sections');
    }
};
