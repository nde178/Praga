@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Теги</h3>
        </div>
    </div>

    <a href="{{ route('tags.create') }}" class="btn btn-primary mb-3">Добавить тег</a>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Порядковый номер</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tags as $tag)
            <tr>
                <td>{{ $tag->order }}</td>
                <td>{{ $tag->name }}</td>
                <td>
                    <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('tags.destroy', $tag) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
