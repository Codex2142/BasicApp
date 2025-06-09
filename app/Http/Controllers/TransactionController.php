<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use App\Providers\WebHelper;

class TransactionController extends Controller
{

    // menuju halaman utama transaksi
    public function index()
    {
        return view('pages.transaction.view');
    }

    // menuju halaman tambah transaksi
    public function create()
    {
        $table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.transaction.add', compact('result'));
    }

    // proses penambahan transaksi
    public function store(Request $request)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo']);

        // jika terdapat photo
        if ($request->hasFile('photo')) {
            // Simpan file dan ambil path relatifnya
            $input['photo'] = $request->file('photo')->store('images', 'public');
        }

        // proses penambahan transaksi
        $table = 'transactions';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);

        // jika terdapat error
        if (isset($result['error'])) {
            return redirect('/transaksi/tambah')->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/transaksi')->with('success', 'Berhasil Menyimpan Data');
    }

    // menuju halaman edit
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        $products = Product::all();

        // mengambil tanggal untuk breadcrumbs
        $transaction->date = WebHelper::dateIndonesia($transaction->tanggal);

        $transaction->product = $transaction->product ?? '{}';

        // dd($transaction, $products);
        return view('pages.transaction.edit', compact('transaction', 'products'));
    }

    // proses update transaksi
    public function update(Request $request, string $id)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo','_method']);

        // jika terdapat photo di $request
        if ($request->hasFile('photo')) {
            $input['photo'] = $request->file('photo')->store('images', 'public');
        }

        $table = 'transactions';
        $data = CrudHelper::table($table);

        // Modifikasi aturan dari Model, unique tanggal untuk ignore record dengan id $id
        if (isset($data['rules']['tanggal'])) {
            $data['rules']['tanggal'] = 'required|unique:' . $table . ',tanggal,' . $id;
        }

        // proses update
        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);

        // jika gagal
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/transaksi')->with('success', 'Berhasil Mengubah Data');
    }

    // mengahpus transaksi
    public function destroy(string $id)
    {
        $table = 'transactions';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/transaksi')->with('success', 'Berhasil Menghapus Data');
    }
}
