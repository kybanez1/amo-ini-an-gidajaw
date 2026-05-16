<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {

            if (!Schema::hasColumn('project_submissions', 'task_id')) {

                $table->unsignedBigInteger('task_id')
                    ->nullable()
                    ->after('project_id');

            }

        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {

            if (Schema::hasColumn('project_submissions', 'task_id')) {

                $table->dropColumn('task_id');

            }

        });
    }
};