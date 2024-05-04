<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Departure;
use App\Models\Dimension;

class AddCorrectDimensionIdToEachDeparture extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('departures', function (Blueprint $table) {
            $departures = Departure::all();
            foreach ($departures as $departure)
            {
                $dimension = Dimension::where('name',$departure->dimensions)->first();
                $departure->dimension_id = $dimension->id;
                $departure->save();
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
        Schema::table('departures', function (Blueprint $table) {
            $departures = Departure::all();
            foreach ($departures as $departure)
            {
                $departure->dimension_id = null;
                $departure->save();
            }
        });
    }
}
