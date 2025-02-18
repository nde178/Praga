@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Редактировать тег</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('tags.update', $tag->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $tag->name }}" required>
                </div>
                <div class="form-group">
                    <label for="order">Порядковый номер</label>
                    <input type="number" class="form-control" name="order" id="order" value="{{ $tag->order }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
@endsection
