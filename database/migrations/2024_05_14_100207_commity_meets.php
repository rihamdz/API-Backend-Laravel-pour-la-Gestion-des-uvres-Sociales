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
        Schema::create('commity_meets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('commity_id');
            $table->unsignedBigInteger('meets_id');
            $table->timestamps();
            $table->foreign('commity_id')->references('id')->on('commities')->onDelete('cascade');
            $table->foreign('meets_id')->references('id')->on('meets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commity_meets');
    }
};
