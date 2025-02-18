@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            @if(isset($category))
                <h3 class="card-title">Продукты категории: {{ $category->name }}</h3>
            @else
                <h3 class="card-title">Все продукты</h3>
            @endif
            <!-- Кнопка добавления товара -->
            <a href="{{ route('products.create') }}" class="btn btn-primary float-right">Добавить товар</a>
        </div>
    </div>

    <!-- Десктопная версия: отображение продуктов в виде таблицы -->
    <div class="table-responsive d-none d-md-block">
        <table class="table mt-3">
            <thead>
            <tr>
                <th>Изображение</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Количество на складе</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td><img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" width="50"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ Str::limit($product->description, 50) }}</td>
                    <td>{{ $product->new_price }} руб.</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Мобильная версия: отображение продуктов в виде карточек -->
    <div class="d-block d-md-none">
        <div class="row">
            @foreach($products as $product)
                <div class="col-12 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $product->main_image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text"><strong>Цена:</strong> {{ $product->new_price }} руб.</p>
                            <p class="card-text"><strong>В наличии:</strong> {{ $product->stock_quantity }}</p>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Редактировать</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
