<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |----------------------------------------------------------------------
        | 1. ADD teacher_code TO users
        |----------------------------------------------------------------------
        */
        if (!Schema::hasColumn('users', 'teacher_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('teacher_code', 8)
                      ->nullable()
                      ->unique()
                      ->after('status');
            });
        }

        // Auto-generate codes for existing teachers
        User::where('role', 'teacher')
            ->whereNull('teacher_code')
            ->each(function ($teacher) {
                do {
                    $code = strtoupper(Str::random(6));
                } while (User::where('teacher_code', $code)->exists());

                $teacher->update(['teacher_code' => $code]);
            });

        /*
        |----------------------------------------------------------------------
        | 2. CREATE teacher_student PIVOT TABLE
        |----------------------------------------------------------------------
        */
        if (!Schema::hasTable('teacher_student')) {
            Schema::create('teacher_student', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('teacher_id');
                $table->unsignedBigInteger('student_id');
                $table->timestamps();

                $table->foreign('teacher_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->foreign('student_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');

                $table->unique(['teacher_id', 'student_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_student');

        if (Schema::hasColumn('users', 'teacher_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('teacher_code');
            });
        }
    }
};