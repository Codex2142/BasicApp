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

    // menuju halaman utama Kiriman
    public function index()
    {
        return view('pages.transaction.view');
    }

    // menuju halaman tambah Kiriman
    public function create()
    {
        $dateDisabled = Transaction::get()->pluck('tanggal')->toArray();
        $result = Product::orderBy('name')->get();
        return view('pages.transaction.add', compact('result', 'dateDisabled'));
    }

    // proses penambahan Kiriman
    public function store(Request $request)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo']);

        // jika terdapat photo
        if ($request->hasFile('photo')) {
            // Simpan file dan ambil path relatifnya
            $input['photo'] = $request->file('photo')->store('images', 'public');
        }

        // proses penambahan Kiriman
        $table = 'transactions';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);

        // jika terdapat error
        if (isset($result['error'])) {
            return redirect('/Kiriman/tambah')->withErrors($result['error']);
        }

        // Validasi stock produk
        $product = Product::get()->toArray();
        $isValid = dataValidator::stockValidator($input['product'], $product, 'add');
        if(!$isValid){
            return redirect()->back()->withErrors('Stock tidak cukup!');
        }

        // jika berhasil
        return redirect('/Kiriman')->with('success', 'Berhasil Menyimpan Data');
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

    // proses update Kiriman
    public function update(Request $request, string $id)
    {
        // Validasi stock produk (1)
        $product = Product::get()->toArray();
        $isValid = $request['updated_stocks'];

        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo','_method', 'updated_stocks']);

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

        // Validasi stock produk (2)
        $isValid = dataValidator::stockValidator($request['updated_stocks'], $product, 'edit');
        if(!$isValid){
            return redirect()->back()->withErrors('Stock tidak cukup!');
        }

        // jika gagal
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/Kiriman')->with('success', 'Berhasil Mengubah Data');
    }

    // mengahpus Kiriman
    public function destroy(string $id)
    {

        // Kiriman done tapi ingin dihapus
        $transaction = Transaction::findOrFail($id);
        if ($transaction->status === 'done') {
            return redirect()->back()->withErrors('Tidak dapat menghapus Kiriman yang sudah selesai.');
        }

        // Validasi stock produk (2)
        $product = Product::get()->toArray();
        $isValid = dataValidator::stockValidator($id, $product, 'delete');
        if(!$isValid){
            return redirect()->back()->withErrors('Stock tidak cukup!');
        }

        $table = 'transactions';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/Kiriman')->with('success', 'Berhasil Menghapus Data');
    }
}
