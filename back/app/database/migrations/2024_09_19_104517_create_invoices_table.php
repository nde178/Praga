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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Внешний ключ на покупателя
            $table->unsignedBigInteger('company_id')->nullable(); // Внешний ключ на компанию (если применимо)
            $table->date('invoice_date');
            $table->decimal('total_amount', 10, 2); // Общая сумма счета
            $table->string('status')->default('unpaid'); // Статус инвойса
            $table->timestamps();

            // Внешний ключи
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
