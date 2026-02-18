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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount');
            $table->unsignedBigInteger('frecuency_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('rate_id');
            $table->timestamps();
            $table->foreign('frecuency_id')->references('id')->on('frecuencies');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('rate_id')->references('id')->on('rates');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
