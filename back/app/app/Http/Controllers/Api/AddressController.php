<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // Получение всех адресов текущего покупателя
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $addresses = $customer->addresses;
        return response()->json($addresses);
    }

    // Добавление нового адреса
    public function store(Request $request)
    {
        $request->validate([
            'address_line_1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);

        $customer = Auth::guard('customer')->user();

        $address = $customer->addresses()->create($request->all());

        return response()->json($address, 201);
    }

    // Удаление адреса
    public function destroy($id)
    {
        $customer = Auth::guard('customer')->user();
        $address = $customer->addresses()->where('id', $id)->first();

        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $address->delete();
        return response()->json(['message' => 'Address deleted successfully'], 200);
    }
}
