<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('project_student')) {
            return;
        }

        Schema::create('project_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('assignment_status')->default('assigned'); // string not enum
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'student_id']);
            $table->index('project_id');
            $table->index('student_id');
            $table->index('assignment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_student');
    }
};
