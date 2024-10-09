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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('full_name');
            $table->string('phone');
            $table->boolean('active')->default(false);
            $table->decimal('salary', 8, 2);
            $table->string('avatar')->default('/home/kali/Desktop/1cs-riham/storage/app/public/images/photo_profile_default.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
