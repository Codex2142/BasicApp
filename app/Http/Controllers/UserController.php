<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // menuju halaman utama user
    public function index()
    {
        $table = 'users';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.user.view', compact('result'));
    }

    // menuju halaman tambah user
    public function create()
    {
        return view('pages.user.add');
    }

    // proses menambahkan user
    public function store(Request $request)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo', 'photo_']);

        // proses penambahan
        $table = 'users';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);

        // jika terdapat error
        if (isset($result['error'])) {
            return redirect('/user/tambah')->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/user')->with('success', 'Berhasil Menyimpan Data');
    }

    // menuju halaman edit
    public function edit(string $id)
    {
        $table = 'users';
        $data = DB::table($table)->where('id', $id)->get()->toArray();
        return view('pages.user.edit', compact('data'));
    }

    // proses update user
    public function update(Request $request, string $id, ?string $source = null)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo', 'photo_', '_method']);

        $table = 'users';
        $data = CrudHelper::table($table);

        // Jika password tidak diisi, hapus dari $input dan rules
        if (empty($input['password'])) {        // jika password kosong, maka
            unset($input['password']);          // menghapus input password = null
            unset($data['rules']['password']);  // menghapus rule password = required
        }                                       // sehingga membiarkan password lama


        // Update aturan username agar unique tapi mengabaikan ID ini
        if (isset($data['rules']['username'])) {
            $data['rules']['username'] = 'required|unique:' . $table . ',username,' . $id;
        }

        // proses update
        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);

        // jika error
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        // jika diedit dari /profil dan BERHASIL
        if ($source === 'profil') {
            return redirect('/profil')->with('success', 'Berhasil Mengubah Profil');
        }

        // jika diedit dari /user dan BERHASIL
        return redirect('/user')->with('success', 'Berhasil Mengubah Data');
    }

    // Fungsi menghapus
    public function destroy(string $id)
    {
        $table = 'users';
        $result = CrudHelper::masterDeleteData($table, $id);
        return redirect('/user')->with('success', 'Berhasil Menghapus Data');
    }
}
