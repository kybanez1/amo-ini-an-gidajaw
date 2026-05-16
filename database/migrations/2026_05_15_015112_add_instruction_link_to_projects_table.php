<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('projects', 'instruction_link')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->string('instruction_link', 2048)
                      ->nullable()
                      ->after('instruction_file_uploaded_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('projects', 'instruction_link')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('instruction_link');
            });
        }
    }
};