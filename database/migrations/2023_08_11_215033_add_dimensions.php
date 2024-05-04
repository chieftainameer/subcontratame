<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Dimension;

class AddDimensions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Dimension::create(['name' => 'Unidad']);
        Dimension::create(['name' => 'Kg']);
        Dimension::create(['name' => 'm3']);
        Dimension::create(['name' => 'm2']);
        Dimension::create(['name' => 'Litro']);
        Dimension::create(['name' => 'ML']);
        Dimension::create(['name' => 'Tn']);
        Dimension::create(['name' => 'dia']);
        Dimension::create(['name' => 'mes']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
