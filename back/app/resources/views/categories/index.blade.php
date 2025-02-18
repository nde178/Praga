@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Категории</h3>
        </div>
    </div>

    <!-- Кнопка добавления категории -->
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Добавить категорию</a>

    <!-- Версия для десктопа (таблица) -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Изображение</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{ $category->order }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="max-width: 50px;">
                    </td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning mr-2">Редактировать</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Версия для мобильных устройств (карточки) -->
    <div class="row d-block d-md-none">
        @foreach($categories as $category)
            <div class="col-12 mb-3">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $category->image) }}" class="card-img-top img-fluid" alt="{{ $category->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text">Порядковый номер: {{ $category->order }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Редактировать</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
