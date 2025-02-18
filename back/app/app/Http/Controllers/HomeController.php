<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Пример данных для графиков
        $visitorsLabels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $visitorsDataThisWeek = [820, 932, 901, 934, 1290, 1330, 1320];
        $visitorsDataLastWeek = [750, 820, 860, 900, 1000, 1100, 1150];

        $salesLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July'];
        $salesDataThisYear = [1500, 2300, 1800, 2200, 2800, 3000, 3500];
        $salesDataLastYear = [1200, 2000, 1500, 1800, 2500, 2700, 3100];

        $products = Product::all(); // Получение списка продуктов

        // Данные для карточек
        $visitorsCount = 820;
        $visitorGrowth = 12.5;
        $salesTotal = 18230;
        $salesGrowth = 33.1;

        return view('layouts.index', compact(
            'visitorsLabels',
            'visitorsDataThisWeek',
            'visitorsDataLastWeek',
            'salesLabels',
            'salesDataThisYear',
            'salesDataLastYear',
            'products',
            'visitorsCount',
            'visitorGrowth',
            'salesTotal',
            'salesGrowth'
        ));
    }
}
