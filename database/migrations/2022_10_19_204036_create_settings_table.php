<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->text('terms_conditions')->nullable();
            $table->text('privacy_policies')->nullable();
            $table->string('contact_cellphone')->nullable();
            $table->string('contact_email')->nullable();
            $table->decimal('price_departure', 8, 2)->nullable()->default(0);
            $table->decimal('price_variable', 8, 2)->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
