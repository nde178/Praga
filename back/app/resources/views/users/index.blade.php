@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Пользователи</h3>
            <a href="{{ route('users.create') }}" class="btn btn-primary float-right">Добавить пользователя</a>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Email</th>
                <th>Дата создания</th>
                <th>Права</th> <!-- Добавлено отображение прав -->
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        <!-- Расшифровываем и отображаем JSON с правами -->
                        @php
                            $permissions = json_decode($user->permissions, true);
                        @endphp

                        @if(is_array($permissions) && count($permissions) > 0)
                            @if($permissions['all'] ?? false)
                                Полный доступ
                            @else
                                <ul>
                                    @foreach($permissions as $permission => $value)
                                        @if($value)
                                            <li>{{ $permission }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        @else
                            Права ограничены
                        @endif

                    </td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Редактировать</a>

                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить пользователя?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
