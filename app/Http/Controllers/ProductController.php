<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Menuju halaman Produk
    public function index()
    {
        $table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.product.view', compact('result'));
    }

    // Menuju Halaman Tambah
    public function create()
    {
        return view('pages.product.add');
    }

    // Proses penambahan data
    public function store(Request $request)
    {
        // Melakukan pengecualian dari $request
        $input = Arr::except($request->all(), ['_token', 'photo', 'photo_']);

        // jika input _photo, maka disimpan di storage
        if ($request->hasFile('photo_')) {
            // Simpan file dan ambil path relatifnya
            $input['photo'] = $request->file('photo_')->store('images', 'public');
        }

        // proses penambahan produk
        $table = 'products';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);

        // Jika terdapat error
        if (isset($result['error'])) {
            return redirect('/produk/tambah')->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/produk')->with('success', 'Berhasil Menyimpan Data');
    }

    // Menuju halaman Edit
    public function edit(string $id)
    {
        $table = 'products';
        $data = DB::table($table)->where('id', $id)->get()->toArray();
        return view('pages.product.edit', compact('data'));
    }

    // Proses Update
    public function update(Request $request, string $id)
    {
        // Melakukan pengecualian dari $request
        $input = Arr::except($request->all(), ['_token', 'photo', 'photo_']);

        // jika input _photo, maka disimpan di storage
        if ($request->hasFile('photo_')) {
            // Simpan file dan ambil path relatifnya
            $input['photo'] = $request->file('photo_')->store('images', 'public');
        }

        // proses update Produk
        $table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);

        // jika terdapat error
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        // jika berhasil
        return  redirect('/produk')->with('success', 'Berhasil Mengubah Data');

    }

    public function destroy(string $id)
    {
        // proses menghapus data
        $table = 'Products';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/produk')->with('success', 'Berhasil Menghapus Data');

    }
}
