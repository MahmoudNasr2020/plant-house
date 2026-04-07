<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite/MySQL/PostgreSQL-safe: recreate column as string
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support ALTER COLUMN directly — recreate table
            DB::statement('CREATE TABLE users_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                role VARCHAR(60) NOT NULL DEFAULT "admin",
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                email_verified_at DATETIME NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100) NULL,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )');
            DB::statement('INSERT INTO users_new SELECT id, name, email, role, is_active, email_verified_at, password, remember_token, created_at, updated_at FROM users');
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');
        } else {
            DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(60) NOT NULL DEFAULT 'admin'");
        }
    }

    public function down(): void
    {
        // No-op: reverting enum constraint isn't trivial
    }
};
