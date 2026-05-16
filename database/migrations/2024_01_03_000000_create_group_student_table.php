<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('group_student')) {
            return;
        }

        Schema::create('group_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('is_joined')->default(0);
            $table->timestamps();

            $table->unique(['group_id', 'student_id']);
            $table->index('group_id');
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_student');
    }
};
