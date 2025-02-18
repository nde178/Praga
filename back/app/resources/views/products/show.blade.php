@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">{{ $product->name }}</h3>
        </div>

        <div class="card-body">
            <p><strong>Цена:</strong> {{ $product->new_price }} руб.</p>
            <p><strong>Описание:</strong> {{ $product->description }}</p>

            <h5>Отзывы</h5>
            @if($product->reviews->isEmpty())
                <p>Пока нет отзывов</p>
            @else
                @foreach($product->reviews as $review)
                    <div class="review">
                        <p><strong>{{ $review->author }}</strong></p>
                        <p>{{ $review->content }}</p>
                        <p>Рейтинг: {{ $review->rating }}/5</p>
                    </div>
                @endforeach
            @endif

            <h5>Оставить отзыв</h5>
            <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="author">Ваше имя</label>
                    <input type="text" class="form-control" name="author" id="author" required>
                </div>
                <div class="form-group">
                    <label for="content">Отзыв</label>
                    <textarea class="form-control" name="content" id="content" required></textarea>
                </div>
                <div class="form-group">
                    <label for="rating">Рейтинг</label>
                    <select class="form-control" name="rating" id="rating">
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Добавить отзыв</button>
            </form>
        </div>
    </div>
@endsection
