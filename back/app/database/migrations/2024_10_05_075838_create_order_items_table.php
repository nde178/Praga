<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Связь с таблицей заказов
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Связь с таблицей продуктов
            $table->integer('qty'); // Количество товара
            $table->decimal('price', 10, 2); // Цена товара
            $table->string('main_image')->nullable(); // Изображение товара
            $table->string('unique_code')->nullable(); // Уникальный код товара
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
