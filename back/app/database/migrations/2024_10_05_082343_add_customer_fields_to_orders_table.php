<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable(); // Имя клиента (если не зарегистрирован)
            $table->string('customer_email')->nullable(); // Email клиента
            $table->string('customer_phone')->nullable(); // Телефон клиента
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_email');
            $table->dropColumn('customer_phone');
        });
    }
};
