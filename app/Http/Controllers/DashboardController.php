<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Providers\WebHelper;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $now = Carbon::now();

        $table = 'Products';
        $data = CrudHelper::table($table);
        $product = CrudHelper::masterShowData($table, $data);

        $transaction = Transaction::get()->toArray();
        $month = Str::upper(WebHelper::getCurrentMonth());


        $total = Transaction::whereMonth('tanggal', $now->month)
                    ->whereYear('tanggal', $now->year)->where('status', 'done')
                    ->sum('total');

        return view('dashboard.index', compact(
            'product',
            'transaction',
            'month',
            'total',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
