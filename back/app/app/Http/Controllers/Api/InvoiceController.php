<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    // Получение всех инвойсов текущего покупателя
    public function index()
    {
        // Получение списка инвойсов с пагинацией
        $invoices = Invoice::with('customer') // Загрузка связанных данных клиента
        ->orderBy('invoice_date', 'desc')
            ->paginate(10); // Указываем, сколько записей показывать на одной странице

        // Передаем данные в шаблон
        return view('invoices.index', compact('invoices'));
    }
    public function getInvoiceData($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json($invoice);
    }
    // Получение конкретного инвойса
    public function show($id)
    {
        // Находим инвойс по ID
        $invoice = Invoice::findOrFail($id);

        // Передаем данные в Blade-шаблон
        return view('invoices.invoice', compact('invoice'));
    }

    // Создание инвойса
    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        $customer = Auth::guard('customer')->user();

        $invoice = $customer->invoices()->create($request->all());

        return response()->json($invoice, 201);
    }
}
