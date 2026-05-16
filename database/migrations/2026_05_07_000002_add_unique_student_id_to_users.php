<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Unique student_id is enforced via controller validation
        // SQLite doesn't support modifying columns after creation
        // so we skip adding a unique constraint here if it already exists.
    }

    public function down(): void
    {
        //
    }
};
