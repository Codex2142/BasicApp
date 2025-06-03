<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.transaction.view');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.transaction.add', compact('result'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = Arr::except($request->all(), ['_token', 'photo']);

        if ($request->hasFile('photo')) {
            // Simpan file dan ambil path relatifnya
            $input['photo'] = $request->file('photo')->store('images', 'public');
        }

        $table = 'transactions';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);
        if (isset($result['error'])) {
            return redirect('/transaksi/tambah')->withErrors($result['error']);
        }
        return redirect('/transaksi')->with('success', 'Berhasil Menyimpan Data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $products = Product::all();
        return view('pages.transaction.edit', compact('transaction', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = Arr::except($request->all(), ['_token', 'photo','_method']);

        if ($request->hasFile('photo')) {
            $input['photo'] = $request->file('photo')->store('images', 'public');
        }

        $table = 'transactions';
        $data = CrudHelper::table($table); // Mengambil fillable, Rules, Messages

        // Modifikasi aturan unique tanggal untuk ignore record dengan id $id
        if (isset($data['rules']['tanggal'])) {
            $data['rules']['tanggal'] = 'required|unique:' . $table . ',tanggal,' . $id;
        }

        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);

        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        return redirect('/transaksi')->with('success', 'Berhasil Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = 'transactions';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/transaksi')->with('success', 'Berhasil Menghapus Data');
    }
}
