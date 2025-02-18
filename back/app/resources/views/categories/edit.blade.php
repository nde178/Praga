@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Редактировать категорию</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $category->name }}" required>
                </div>

                <div class="form-group">
                    <label for="order">Порядковый номер</label>
                    <input type="number" class="form-control" name="order" id="order" value="{{ $category->order }}" required>
                </div>

                <div class="form-group">
                    <label for="image">Изображение</label>
                    <input type="file" class="form-control" name="image" id="image" onchange="previewImage(event)">
                </div>

                <!-- Превью изображения, будет обновляться при выборе нового -->
                <div class="form-group">
                    <img id="imagePreview" src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="max-width: 200px;">
                </div>

                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            // Проверим, был ли выбран файл
            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Установим новый src для img
                    var output = document.getElementById('imagePreview');
                    output.src = e.target.result;
                    output.style.display = 'block'; // Покажем изображение
                }

                // Чтение нового файла
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
@endsection
