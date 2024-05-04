<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('expiration_date')->nullable();
            $table->integer('quantity')->nullable();
            // 1 - Original
            // 2 - Alternativo
            $table->enum('type', [1,2])->comment('1 - Original, 2 - Alternativo')->nullable();
            // 1 - Suministro
            // 2 - Instalación
            // 3 - Instalación + Suministro
            $table->enum('includes', [1, 2, 3])->comment('1 - Suministro, 2 - Instalación, 3 - Instalación +  Suministro')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->boolean('iva')->default(0);
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
        Schema::dropIfExists('variants');
    }
}
