<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'city') && !Schema::hasColumn('users', 'country')) {
            DB::statement('ALTER TABLE users CHANGE city country VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'country') && !Schema::hasColumn('users', 'city')) {
            DB::statement('ALTER TABLE users CHANGE country city VARCHAR(255) NULL');
        }
    }
};

