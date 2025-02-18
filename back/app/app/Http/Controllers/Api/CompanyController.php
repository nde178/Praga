<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    // Получение данных о компании текущего покупателя
    public function index()
    {
        $customer = Auth::guard('customer')->user();
        $company = $customer->company;
        return response()->json($company);
    }

    // Обновление данных о компании
    public function update(Request $request)
    {
        $customer = Auth::guard('customer')->user();
        $company = $customer->company;

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $company->update($request->all());

        return response()->json($company, 200);
    }
}

