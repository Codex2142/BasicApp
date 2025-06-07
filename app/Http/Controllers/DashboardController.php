<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Providers\WebHelper;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();
        $year = (int) $request->input('year', $now->year);
        $month = (int) $request->input('month', $now->month);


        $monthInd = WebHelper::getCurrentMonth();

        $table = 'Products';
        $data = CrudHelper::table($table);
        $product = CrudHelper::masterShowData($table, $data);

        $transaction = Transaction::whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('status', 'done')
            ->orderBy('tanggal')
            ->get();

        $total = $transaction->sum('total');

        $grouped = $transaction->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('d M');
        });

        $labels = $grouped->keys()->toArray();
        $totals = $grouped->map(fn($group) => $group->sum('total'))->values()->toArray();

        $chart = LarapexChart::barChart()
            ->addData('Total', $totals)
            ->setXAxis($labels)
            ->setHeight(400);

        return view('dashboard.index', compact(
            'product',
            'transaction',
            'month',
            'monthInd',
            'total',
            'chart',
            'year',
            'now',
            ));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $table = 'users';
        $id = Auth::id();
        $data = DB::table($table)->where('id', $id)->get()->toArray();
        dd($data);
        return view('dashboard.settings', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
