<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use App\Providers\WebHelper;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;

class DashboardController extends Controller
{
    // Menuju Halaman dashboard utama
    public function index(Request $request)
    {
        // Mendapatkan tanggal
        $now = Carbon::now();
        $year = (int) $request->input('year', $now->year);
        $month = (int) $request->input('month', $now->month);

        // Mendapatkan bulan dengan Format MM
        $monthInd = WebHelper::getCurrentMonth();

        // Mendapatkan semua produk
        $table = 'Products';
        $data = CrudHelper::table($table);
        $product = CrudHelper::masterShowData($table, $data);

        // Mendapatkan semua transaksi yang sudah selesai di bulan N
        $transaction = Transaction::whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('status', 'done')
            ->orderBy('tanggal')
            ->get();

        // Mendapatkan total User
        $users = User::get();
        $users = $users->count('id');

        // Mendapatkan Total Pendapatan bulan N
        $total = $transaction->sum('total');

        // Mendapatkan sumbu X untuk chart
        $grouped = $transaction->groupBy(function ($item) {
            return Carbon::parse($item->tanggal)->format('d M');
        });

        // Mendapatkn sumbu Y untuk chart
        $labels = $grouped->keys()->toArray();
        $totals = $grouped->map(fn($group) => $group->sum('total'))->values()->toArray();

        // Pembuatan Chart
        $chart = LarapexChart::barChart()
            ->addData('Total', $totals)
            ->setColors(['#443627'])
            ->setXAxis($labels)
            ->setHeight(400);

        // menuju dashboard dengan parameter
        return view('dashboard.index', compact(
            'product',
            'transaction',
            'month',
            'monthInd',
            'total',
            'chart',
            'year',
            'now',
            'users',
            ));
    }

    // menuju ke Profil
    public function show()
    {
        $table = 'users';
        $id = Auth::id();
        $data = DB::table($table)->where('id', $id)->get()->toArray();
        return view('dashboard.settings', compact('data'));
    }
}
