<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('catalog')) {
            return;
        }

        Schema::create('catalog', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';

            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->string('image')->nullable()->default('');
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();

            $table->index('parent_id', 'parent_id');
            $table->index('name', 'name');
            $table->foreign('parent_id', 'catalog_parent_id_foreign')
                ->references('id')
                ->on('catalog')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog');
    }
}
