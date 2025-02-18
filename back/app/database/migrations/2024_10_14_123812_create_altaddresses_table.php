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
        Schema::create('altaddresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Связь с customers
            $table->string('name')->nullable(); // Jméno a příjmení
            $table->string('company')->nullable(); // Firma (может быть пустым)
            $table->string('street')->nullable(); // Ulice
            $table->string('city')->nullable(); // Město
            $table->string('postal_code')->nullable(); // PSČ
            $table->string('contact_phone')->nullable(); // Kontaktní telefon pro dopravce
            $table->timestamps();

            // Добавляем внешний ключ к таблице customers
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('altaddresses');
    }
};
