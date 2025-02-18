@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Роли</h3>
            <a href="{{ route('roles.create') }}" class="btn btn-primary float-right">Добавить роль</a>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Название роли</th>
                <th>Slug</th>
                <th>Права (Permissions)</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->slug }}</td>
                    <td>
                        @php
                            $permissions = json_decode($role->permissions, true);
                        @endphp
                        @if($permissions && is_array($permissions))
                            <ul>
                                @foreach($permissions as $permission => $value)
                                    @if($value)
                                        <li>{{ $permission }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            Нет прав
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Редактировать</a>

                        <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить роль?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
