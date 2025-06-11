<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use App\Providers\WebHelper;
use App\Rules\dataValidator;

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
        $dateDisabled = Transaction::get()->pluck('tanggal')->toArray();
        $result = Product::orderBy('name')->get();
        return view('pages.transaction.add', compact('result', 'dateDisabled'));
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
        $products = Product::orderBy('name')->get();

        // Mengambil tanggal yang sudah diisi
        $dateDisabled = Transaction::get()->pluck('tanggal')->toArray();
        $dateDisabled = array_diff($dateDisabled, [$transaction->tanggal]);

        // mengambil tanggal untuk breadcrumbs
        $transaction->date = WebHelper::dateIndonesia($transaction->tanggal);

        $transaction->product = $transaction->product ?? '{}';

        return view('pages.transaction.edit', compact('transaction', 'products', 'dateDisabled'));
    }

    // proses update transaksi
    public function update(Request $request, string $id)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo','_method']);

        $productJson = $request->input('product');
        if (dataValidator::isEmptyJson($productJson)) {
            return back()->withErrors(['product' => 'Pilih minimal 1 produk!'])->withInput();
        }

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

        // Transaksi done tapi ingin dihapus
        $transaction = Transaction::findOrFail($id);
        if ($transaction->status === 'done') {
            return redirect()->back()->withErrors('Tidak dapat menghapus transaksi yang sudah selesai.');
        }

        $table = 'transactions';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/transaksi')->with('success', 'Berhasil Menghapus Data');
    }
}
