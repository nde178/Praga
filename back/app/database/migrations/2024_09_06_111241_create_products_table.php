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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->default(0);                    // Порядковый номер
            $table->string('name');                                  // Название
            $table->decimal('old_price', 10, 2)->nullable();         // Старая цена
            $table->decimal('new_price', 10, 2);                     // Новая цена
            $table->string('main_image');                            // Главное изображение
            $table->json('carousel_images')->nullable();             // Изображения для карусели (сохраняются как JSON)
            $table->integer('stock_quantity');                       // Количество на складе
            $table->string('unique_code')->unique();                 // Уникальный код товара
            $table->string('slug')->unique();                        // Slug для удобной ссылки
            $table->unsignedBigInteger('category_id');               // Ссылка на категорию
            $table->softDeletes(); // Добавляем мягкое удаление
            $table->timestamps();

            // Внешние ключи
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
        Schema::create('product_tag', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
