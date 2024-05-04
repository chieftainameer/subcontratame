<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdInAutonomousCommunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('autonomous_community', function (Blueprint $table) {
            $table->foreignId("country_id")
            ->default(1)
            ->constrained("pais")
            ->onUpdate("cascade")
            ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autonomous_community', function (Blueprint $table) {
            $table->dropForeign(["country_id"]);
        });
    }
}
