<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('group_student') && !Schema::hasColumn('group_student', 'is_joined')) {
            Schema::table('group_student', function (Blueprint $table) {
                $table->tinyInteger('is_joined')->default(0)->after('student_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('group_student', 'is_joined')) {
            Schema::table('group_student', function (Blueprint $table) {
                $table->dropColumn('is_joined');
            });
        }
    }
};
