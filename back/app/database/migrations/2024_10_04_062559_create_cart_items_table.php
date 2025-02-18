<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade'); // Ссылка на корзину
            $table->foreignId('product_id')->constrained('products'); // ID товара
            $table->integer('quantity'); // Количество товара
            $table->decimal('price', 10, 2); // Цена за единицу товара
            $table->decimal('total_price', 10, 2); // Общая цена за товар
            $table->timestamps(); // Время создания и обновления записи
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
}
