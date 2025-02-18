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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Внешний ключ на таблицу покупателей
            $table->string('company_name');
            $table->string('ico_number')->nullable(); // Налоговый номер
            $table->string('dic_number')->nullable(); // Налоговый номер
            $table->string('company_address');
            $table->string('phone')->nullable();
            $table->string('email');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
