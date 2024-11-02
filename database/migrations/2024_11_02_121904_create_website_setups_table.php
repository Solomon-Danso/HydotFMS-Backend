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
        Schema::create('website_setups', function (Blueprint $table) {
            $table->id();
            $table->longText('CompanyLogo')->nullable();
            $table->longText('CompanyName')->nullable();
            $table->longText('ShopURL')->nullable();
            $table->longText('Location')->nullable();
            $table->longText('PhoneNumber')->nullable();
            $table->longText('Email')->nullable();
            $table->longText('Whatsapp')->nullable();
            $table->longText('Instagram')->nullable();
            $table->longText('Facebook')->nullable();
            $table->longText('LinkedIn')->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_setups');
    }
};
