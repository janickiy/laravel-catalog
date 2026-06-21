<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveHtmlcodeBanneLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('links', 'htmlcode_banner')) {
            Schema::table('links', function (Blueprint $table) {
                $table->dropColumn('htmlcode_banner');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('links', 'htmlcode_banner')) {
            Schema::table('links', function (Blueprint $table) {
                $table->text('htmlcode_banner')->nullable();
            });
        }
    }
}
