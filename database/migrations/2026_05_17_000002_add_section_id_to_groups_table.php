<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('groups') && !Schema::hasColumn('groups', 'section_id')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->foreignId('section_id')
                      ->nullable()
                      ->after('teacher_id')
                      ->constrained('sections')
                      ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('groups') && Schema::hasColumn('groups', 'section_id')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            });
        }
    }
};
