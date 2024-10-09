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
    Schema::create('minhas', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('employee_id');
        $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        $table->string('offer_title'); 
        $table->string('first_name');      
        $table->string('last_name');              
        $table->string('phone');
        $table->string('second_phone')->nullable();
        $table->enum('corp', ['prof', 'administrateur', 'worker']); 
        $table->string('rank');
        $table->longText('justification');
        $table->date('date_employment');
        $table->enum('state', ['complet', 'incomplet', 'en attente', 'refus' , 'accepte']); 
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minhas');

    }
};
