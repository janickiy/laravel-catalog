<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Добавляет email к ссылкам в базах, созданных из старого дампа.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('links', 'email')) {
            Schema::table('links', function (Blueprint $table): void {
                $table->string('email')->nullable()->after('city');
            });
        }
    }

    /**
     * Удаляет добавленную колонку email из таблицы ссылок.
     */
    public function down(): void
    {
        if (Schema::hasColumn('links', 'email')) {
            Schema::table('links', function (Blueprint $table): void {
                $table->dropColumn('email');
            });
        }
    }
};
