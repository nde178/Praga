@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Редактировать продукт: {{ $product->name }}</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $product->name }}" required maxlength="255">
                </div>

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea class="form-control" name="description" id="description" required>{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="old_price">Старая цена</label>
                    <input type="number" class="form-control" name="old_price" id="old_price" value="{{ $product->old_price }}" min="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="new_price">Новая цена</label>
                    <input type="number" class="form-control" name="new_price" id="new_price" value="{{ $product->new_price }}" required min="0" step="0.01">
                </div>

                <div class="form-group">
                    <label for="main_image">Главное изображение</label>
                    <input type="file" class="form-control" name="main_image" id="main_image" accept="image/*">
                    @if ($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" width="100" class="img-thumbnail mt-2">
                    @endif
                </div>

                <div class="form-group">
                    <label for="carousel_images">Изображения для карусели</label>
                    <input type="file" class="form-control" name="carousel_images[]" id="carousel_images" multiple accept="image/*">
                    @if ($product->carousel_images)
                        <div class="mt-2">
                            @foreach(json_decode($product->carousel_images, true) as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Карусельное изображение" width="100" class="img-thumbnail">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="stock_quantity">Количество на складе</label>
                    <input type="number" class="form-control" name="stock_quantity" id="stock_quantity" value="{{ $product->stock_quantity }}" required min="0" step="1">
                </div>

                <div class="form-group">
                    <label for="unique_code">Уникальный код товара</label>
                    <input type="text" class="form-control" name="unique_code" id="unique_code" value="{{ $product->unique_code }}" required maxlength="50">
                </div>

                <div class="form-group">
                    <label for="category_id">Категория</label>
                    <select class="form-control" name="category_id" id="category_id" required>
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Теги</label>
                    <select class="form-control" name="tags[]" id="tags" multiple required>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
@endsection
