<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('projects')) {
            return;
        }

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            $table->text('requirements')->nullable();
            $table->string('instruction_file')->nullable();
            $table->string('instruction_file_name')->nullable();
            $table->timestamp('instruction_file_uploaded_at')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('due_date');
            $table->string('status')->default('draft'); // string not enum — SQLite safe
            $table->integer('max_score')->default(100);
            $table->timestamps();

            $table->index('teacher_id');
            $table->index('group_id');
            $table->index('status');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
