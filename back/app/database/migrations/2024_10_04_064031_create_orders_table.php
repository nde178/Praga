<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->string('delivery_method')->nullable()->after('total_price'); // Способ доставки
            $table->decimal('delivery_price', 10, 2)->default(0)->after('delivery_method'); // Цена доставки
            $table->string('payment_method')->nullable()->after('delivery_price'); // Способ оплаты
            $table->decimal('payment_price', 10, 2)->default(0)->after('payment_method'); // Цена оплаты
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');  // По умолчанию заказ в статусе "в ожидании"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
