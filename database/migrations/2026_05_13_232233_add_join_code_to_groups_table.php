<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Group;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('groups', 'join_code')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->string('join_code', 8)
                      ->nullable()
                      ->unique()
                      ->after('status');
            });
        }

        // Generate codes for existing groups that don't have one
        Group::whereNull('join_code')->each(function ($group) {
            $group->update([
                'join_code' => strtoupper(Str::random(6)),
            ]);
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('groups', 'join_code')) {
            Schema::table('groups', function (Blueprint $table) {
                $table->dropColumn('join_code');
            });
        }
    }
};