<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $table = 'users';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.user.view', compact('result'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.user.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = Arr::except($request->all(), ['_token', 'photo', 'photo_']);
        $table = 'users';
        $config = CrudHelper::table($table);

        $result = CrudHelper::masterInsertData($table, $config, $input);
        if (isset($result['error'])) {
            return redirect('/user/tambah')->withErrors($result['error']);
        }
        return redirect('/user')->with('success', 'Berhasil Menyimpan Data');
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
        $table = 'users';
        $data = DB::table($table)->where('id', $id)->get()->toArray();
        return view('pages.user.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = Arr::except($request->all(), ['_token', 'photo', 'photo_']);
        $table = 'users';
        $data = CrudHelper::table($table);

        // Jika password tidak diisi, hapus dari $input dan rules
        if (empty($input['password'])) {
            unset($input['password']);
            unset($data['rules']['password']);
        }

        // Update aturan username agar unique tapi mengabaikan ID ini
        if (isset($data['rules']['username'])) {
            $data['rules']['username'] = 'required|unique:' . $table . ',username,' . $id;
        }

        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        return redirect('/user')->with('success', 'Berhasil Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $table = 'users';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/user')->with('success', 'Berhasil Menghapus Data');
    }
}
