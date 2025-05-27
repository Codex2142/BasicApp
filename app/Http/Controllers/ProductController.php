<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index()
    {
        $table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.poduct.view', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.poduct.add');
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

        $table = 'products';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);
        if (isset($result['error'])) {
            return redirect('/produk/tambah')->withErrors($result['error']);
        }
        return redirect('/produk')->with('success', 'Berhasil Menyimpan Data');
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
        $table = 'products';
        $data = DB::table($table)->where('id', $id)->get()->toArray();
        return view('pages.poduct.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = Arr::except($request->all(), ['_token', 'photo']);

        if ($request->hasFile('photo')) {
            $input['photo'] = $request->file('photo')->store('images', 'public');
        }

        $table = 'Products';
        $data = CrudHelper::table($table); // Mengambil fillable, Rules, Messages
        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }
        return  redirect('/produk')->with('success', 'Berhasil Mengubah Data');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = 'Products';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/produk')->with('success', 'Berhasil Menghapus Data');

    }
}
