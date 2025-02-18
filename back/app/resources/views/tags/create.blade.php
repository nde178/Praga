@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Создать тег</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('tags.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="order">Порядковый номер</label>
                    <input type="number" class="form-control" name="order" id="order" value="{{ $nextOrder }}" required>
                </div>
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            </form>
        </div>
    </div>
@endsection
