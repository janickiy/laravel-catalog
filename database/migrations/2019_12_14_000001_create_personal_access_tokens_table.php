<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
            });

            return;
        }

        // MySQL/MariaDB can leave the table behind when adding an index fails.
        // Repair that incomplete migration without dropping existing tokens.
        $connection = Schema::getConnection();
        if (in_array($connection->getDriverName(), ['mysql', 'mariadb'], true)) {
            $table = $connection->selectOne(
                'SELECT ENGINE AS engine FROM information_schema.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
                [$connection->getDatabaseName(), $connection->getTablePrefix().'personal_access_tokens']
            );

            if ($table && strcasecmp($table->engine, 'InnoDB') !== 0) {
                $tableName = $connection->getSchemaGrammar()->wrapTable('personal_access_tokens');
                $connection->statement('ALTER TABLE '.$tableName.' ENGINE = InnoDB');
            }
        }

        $hasTokenableIndex = Schema::hasIndex('personal_access_tokens', ['tokenable_type', 'tokenable_id']);
        $hasTokenIndex = Schema::hasIndex('personal_access_tokens', ['token'], 'unique');

        Schema::table('personal_access_tokens', function (Blueprint $table) use ($hasTokenableIndex, $hasTokenIndex) {
            if (! $hasTokenableIndex) {
                $table->index(['tokenable_type', 'tokenable_id']);
            }
            if (! $hasTokenIndex) {
                $table->unique('token');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_access_tokens');
    }
}
