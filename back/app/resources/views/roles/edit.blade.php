@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Редактировать роль</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Поле Название роли -->
                <div class="form-group">
                    <label for="name">Название роли</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $role->name) }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Поле Slug -->
                <div class="form-group">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" id="slug" value="{{ old('slug', $role->slug) }}" required>
                    @error('slug')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Поле Права (Permissions) -->
                <div class="form-group">
                    <label for="permissions">Права (Permissions)</label>
                    <textarea name="permissions" id="permissions" class="form-control @error('permissions') is-invalid @enderror" rows="4" required>{{ old('permissions', json_encode($role->permissions, JSON_PRETTY_PRINT)) }}</textarea>
                    <small class="form-text text-muted">Введите права в формате JSON, например: {"create": true, "edit": false}</small>
                    @error('permissions')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Обновить роль</button>
            </form>
        </div>
    </div>
@endsection
