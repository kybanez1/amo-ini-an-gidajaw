<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only run if the projects table exists but is missing these columns
        // (projects migration now creates them directly, so this is a safety fallback)
        if (!Schema::hasTable('projects')) {
            return;
        }

        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'instruction_file')) {
                $table->string('instruction_file')->nullable()->after('requirements');
            }
            if (!Schema::hasColumn('projects', 'instruction_file_name')) {
                $table->string('instruction_file_name')->nullable()->after('instruction_file');
            }
            if (!Schema::hasColumn('projects', 'instruction_file_uploaded_at')) {
                $table->timestamp('instruction_file_uploaded_at')->nullable()->after('instruction_file_name');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('projects')) {
            return;
        }
        Schema::table('projects', function (Blueprint $table) {
            foreach (['instruction_file', 'instruction_file_name', 'instruction_file_uploaded_at'] as $col) {
                if (Schema::hasColumn('projects', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
