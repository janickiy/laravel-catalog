<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_custom_fields', function (Blueprint $table): void {
            $table->id();
            $table->string('label', 120);
            $table->string('type', 20);
            $table->string('help_text', 255)->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_public')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('links', function (Blueprint $table): void {
            $table->json('custom_fields')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table): void {
            $table->dropColumn('custom_fields');
        });

        Schema::dropIfExists('link_custom_fields');
    }
};
