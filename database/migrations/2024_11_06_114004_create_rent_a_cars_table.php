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
        Schema::create('rent_a_cars', function (Blueprint $table) {
            $table->id();
            $table->longText("RentACarID")->nullable();
            $table->longText("CoverType")->nullable();
            $table->longText("Src")->nullable();
            $table->longText("Title")->nullable();
            $table->longText("SubTitle")->nullable();
            $table->longText("YearModel")->nullable();
            $table->decimal("Price")->default(0);
            $table->longText("GearType")->nullable();
            $table->longText("FuelType")->nullable();
            $table->longText("DetailedPicture")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rent_a_cars');
    }
};
