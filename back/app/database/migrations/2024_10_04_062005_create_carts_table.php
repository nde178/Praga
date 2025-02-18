<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('cascade'); // ID клиента, если авторизован
            $table->string('email')->nullable(); // Email для неавторизованного пользователя
            $table->decimal('total_price_without_extras', 10, 2)->nullable(); // Общая сумма товаров без учета доставки и оплаты
            $table->decimal('delivery_price', 10, 2)->nullable(); // Цена за доставку
            $table->string('delivery_method')->nullable(); // Способ доставки
            $table->decimal('payment_method_price', 10, 2)->nullable(); // Цена за оплату
            $table->string('payment_method_name')->nullable(); // Способ оплаты
            $table->decimal('final_total_price', 10, 2)->nullable(); // Итоговая сумма заказа
            $table->text('message')->nullable(); // Сообщение от клиента
            $table->boolean('newsletter_accepted')->default(false); // Согласие на подписку на рассылку
            $table->boolean('profile_creation_accepted')->default(false); // Согласие на создание профиля
            $table->string('status')->default('pending'); // Статус заказа (pending, abandoned и т.д.)
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
        Schema::dropIfExists('carts');
    }
}
