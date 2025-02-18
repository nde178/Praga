<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbandonedCartEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abandoned_cart_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->onDelete('cascade'); // Ссылка на корзину
            $table->string('email'); // Email для отправки уведомления
            $table->boolean('sent')->default(false); // Было ли письмо отправлено
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
        Schema::dropIfExists('abandoned_cart_emails');
    }
}
