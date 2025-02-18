<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Пример данных для карточек
        $salesToday = 500; // Получите фактические данные
        $visitorsToday = 1500; // Получите фактические данные
        $pendingOrders = 45; // Получите фактические данные
        $totalEarnings = 12000; // Получите фактические данные

        // Количество посетителей (например, за неделю)
        $visitorsCount = 820; // Получите фактические данные
        $visitorGrowth = 1240;
        $salesTotal = 800;
        $salesGrowth = 380;

        // Пример данных для графиков
        $visitorsLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $visitorsDataThisWeek = [820, 932, 901, 934, 1290, 1330, 1320];
        $visitorsDataLastWeek = [750, 820, 860, 900, 1000, 1100, 1150];

        $salesLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
        $salesDataThisYear = [1500, 2300, 1800, 2200, 2800, 3000, 3500];
        $salesDataLastYear = [1200, 2000, 1500, 1800, 2500, 2700, 3100];

        // Получение данных о продуктах
        $products = Product::all();


        // Передача переменных в представление
        return view('layouts.index', compact(
            'salesToday',
            'visitorsToday',
            'pendingOrders',
            'totalEarnings',
            'visitorsLabels',
            'visitorsCount',
            'visitorGrowth',
            'salesGrowth',
            'salesTotal',
            'visitorsDataThisWeek',
            'visitorsDataLastWeek',
            'salesLabels',
            'salesDataThisYear',
            'salesDataLastYear',
            'products'
        ));
    }
}
