@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Создать продукт</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" id="name" required maxlength="255" placeholder="Введите название продукта">
                </div>

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea class="form-control" name="description" id="description" required minlength="10" placeholder="Введите описание продукта"></textarea>
                </div>

                <div class="form-group">
                    <label for="old_price">Старая цена</label>
                    <input type="number" class="form-control" name="old_price" id="old_price" min="0" step="0.01" placeholder="Введите старую цену">
                </div>

                <div class="form-group">
                    <label for="new_price">Новая цена</label>
                    <input type="number" class="form-control" name="new_price" id="new_price" required min="0" step="0.01" placeholder="Введите новую цену">
                </div>

                <div class="form-group">
                    <label for="main_image">Главное изображение</label>
                    <input type="file" class="form-control" name="main_image" id="main_image" required accept="image/*">
                </div>

                <div class="form-group">
                    <label for="carousel_images">Изображения для карусели</label>
                    <input type="file" class="form-control" name="carousel_images[]" id="carousel_images" multiple accept="image/*">
                </div>

                <div class="form-group">
                    <label for="stock_quantity">Количество на складе</label>
                    <input type="number" class="form-control" name="stock_quantity" id="stock_quantity" required min="0" step="1" placeholder="Введите количество">
                </div>

                <div class="form-group">
                    <label for="unique_code">Уникальный код товара</label>
                    <input type="text" class="form-control" name="unique_code" id="unique_code" required maxlength="50" placeholder="Введите уникальный код">
                </div>

                <div class="form-group">
                    <label for="slug">Slug (необязательно)</label>
                    <input type="text" class="form-control" name="slug" id="slug" maxlength="255" placeholder="Slug продукта (если не введено, будет сгенерирован автоматически)">
                </div>

                <div class="form-group">
                    <label for="category_id">Категория</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Теги</label>
                    <select class="form-control" name="tags[]" id="tags" multiple required>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Создать</button>
            </form>
        </div>
    </div>
@endsection
