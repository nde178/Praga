@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Редактировать пользователя</h3>
        </div>

        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Поле Имя -->
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Поле Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Поле Права (Permissions) -->
                <div class="form-group">
                    <label for="permissions">Права</label>
                    @php
                        $permissions = json_decode($user->permissions, true);
                    @endphp
                    <div class="form-group">
                        <label for="permissions">Права</label>
                        @php
                            $permissions = json_decode($user->permissions, true);
                        @endphp
                        <select name="permissions[all]" id="permissions" class="form-control">
                            <option value="false" {{ (isset($permissions['all']) && $permissions['all'] == false) ? 'selected' : '' }}>Ограниченные права</option>
                            <option value="true" {{ (isset($permissions['all']) && $permissions['all'] == true) ? 'selected' : '' }}>Полный доступ</option>
                        </select>
                    </div>
                </div>

                <!-- Поле Пароль -->
                <div class="form-group">
                    <label for="password">Новый пароль (оставьте пустым, если не нужно менять)</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Подтверждение Пароля -->
                <div class="form-group">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                </div>

                <button type="submit" class="btn btn-primary">Обновить</button>
            </form>
        </div>
    </div>
@endsection
