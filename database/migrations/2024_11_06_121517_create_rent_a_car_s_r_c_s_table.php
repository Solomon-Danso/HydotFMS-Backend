<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rent_a_car_s_r_c_s', function (Blueprint $table) {
            $table->id();
            $table->longText("RentACarID")->nullable();
            $table->longText("CoverType")->nullable();
            $table->longText("Src")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_a_car_s_r_c_s');
    }
};
