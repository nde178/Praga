@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Создать категорию</h3>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Название</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <!-- Элемент img для отображения предварительного просмотра изображения -->
                    <img id="imagePreview" src="#" alt="Ваше изображение" style="max-width: 200px; display: none;">
                </div>
                <div class="form-group">
                    <label for="image">Изображение</label>
                    <input type="file" class="form-control" name="image" id="image" onchange="previewImage(event)" required>
                </div>
                <button type="submit" class="btn btn-primary">Создать</button>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event) {
            // Проверим, был ли выбран файл
            if (event.target.files && event.target.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    // Установим src для img и покажем его
                    var output = document.getElementById('imagePreview');
                    output.src = e.target.result;
                    output.style.display = 'block'; // Покажем изображение
                }

                // Чтение файла
                reader.readAsDataURL(event.target.files[0]);
            } else {
                console.error('Файл изображения не выбран');
            }
        }
    </script>
@endsection
