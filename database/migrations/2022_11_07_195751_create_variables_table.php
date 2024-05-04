<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained()->onDelete('cascade');
            // 1 - Simple Options, 
            // 2 - Multiple Options, 
            // 3 - Download Information, 
            // 4 - Upload Request,  
            // 5 - Text
            $table->enum('type', [1,2,3,4,5])->comment('1 - Simple Options, 2 - Multiple Options, 3 - Download Information, 4 - Upload Request, 5 - Text');
            $table->json('options')->nullable();
            $table->boolean('required')->default(0);
            $table->boolean('visible')->default(1);
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
        Schema::dropIfExists('variables');
    }
}
