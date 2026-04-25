<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Roles table ──
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // super_admin, moderator, finance
            $table->string('display_name');          // Super Admin, Moderator, Finance
            $table->string('color')->default('primary'); // badge color
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // ── 2. Permissions table ──
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();        // dashboard.view, users.view etc
            $table->string('display_name');          // View Dashboard
            $table->string('group');                 // Dashboard, User Management etc
            $table->timestamps();
        });

        // ── 3. Role-Permission pivot ──
        Schema::create('role_permission', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->foreignId('permission_id')->constrained('permissions')->onDelete('cascade');
            $table->primary(['role_id', 'permission_id']);
        });

        // ── 4. Add role_id to users table ──
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null')->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};