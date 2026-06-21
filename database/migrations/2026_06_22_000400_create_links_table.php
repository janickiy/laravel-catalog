<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('links')) {
            return;
        }

        Schema::create('links', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb3';
            $table->collation = 'utf8mb3_unicode_ci';

            $table->increments('id');
            $table->string('name');
            $table->string('url')->nullable()->default('');
            $table->string('phone')->nullable()->default('');
            $table->string('city')->nullable()->default('');
            $table->string('email')->nullable();
            $table->text('description');
            $table->text('keywords')->nullable();
            $table->text('full_description');
            $table->unsignedInteger('catalog_id')->nullable();
            $table->string('status', 10);
            $table->integer('views')->default(0);
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
            $table->string('image')->nullable();

            $table->index('catalog_id', 'catalog_id');
            $table->index('url', 'url');
            $table->fullText('name', 'name');
            $table->foreign('catalog_id', 'links_catalog_id_foreign')
                ->references('id')
                ->on('catalog')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
}
