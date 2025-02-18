@extends('layouts.app')

@section('content')
    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Инвойсы</h3>
            <a href="#" class="btn btn-primary float-right" onclick="alert('Добавление инвойсов доступно только через заказы!')">Добавить инвойс</a>
        </div>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Номер</th>
                <th>Клиент</th>
                <th>Дата инвойса</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{ $invoice->number }}</td>
                    <td>{{ $invoice->customer->name ?? 'Неизвестный клиент' }}</td>
                    <td>{{ $invoice->invoice_date->format('d.m.Y') }}</td>
                    <td>{{ number_format($invoice->total_amount, 2, ',', ' ') }} Kč</td>
                    <td>
                        @if($invoice->status === 'paid')
                            <span class="badge badge-success">Оплачено</span>
                        @else
                            <span class="badge badge-warning">Не оплачено</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">Просмотр</a>
                        <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">Редактировать</a>

                        <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить инвойс?')">Удалить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Пагинация -->
    <div class="mt-4">
        {{ $invoices->links() }}
    </div>
@endsection
