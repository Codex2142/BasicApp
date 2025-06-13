<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;
use App\Providers\WebHelper;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // Menuju halaman Produk
    public function index()
    {
        $table = 'products';
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
        $pendingTransactions = Transaction::where('status', 'pending')->orderBy('tanggal')->get();

        $tanggalTerpakai = []; // array untuk menampung tanggal Kiriman yang pakai produk ini

        foreach ($pendingTransactions as $transaction) {
            $items = json_decode($transaction->product, true);

            if (!empty($items['items'])) {
                foreach ($items['items'] as $item) {
                    if ((string) $item['id'] === $id) {
                        // tambahkan tanggal ke array
                        $tanggalTerpakai[] = WebHelper::dateIndonesia($transaction->tanggal);;
                        break; // cukup sekali per Kiriman
                    }
                }
            }
        }

        if (!empty($tanggalTerpakai)) {

            // Gabungkan semua tanggal jadi satu string
            $pesanTanggal = implode(', ', $tanggalTerpakai);

            return redirect('/produk')->withErrors("Masih Ada Kiriman: $pesanTanggal");
        }

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
        $table = 'products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);

        // jika terdapat error
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/produk')->with('success', 'Berhasil Mengubah Data');
    }

    public function destroy(string $id)
    {

        $pendingTransactions = Transaction::where('status', 'pending')->orderBy('tanggal')->get();

        $tanggalTerpakai = []; // array untuk menampung tanggal Kiriman yang pakai produk ini

        foreach ($pendingTransactions as $transaction) {
            $items = json_decode($transaction->product, true);

            if (!empty($items['items'])) {
                foreach ($items['items'] as $item) {
                    if ((string) $item['id'] === $id) {
                        // tambahkan tanggal ke array
                        $tanggalTerpakai[] = WebHelper::dateIndonesia($transaction->tanggal);;
                        break; // cukup sekali per Kiriman
                    }
                }
            }
        }

        if (!empty($tanggalTerpakai)) {

            // Gabungkan semua tanggal jadi satu string
            $pesanTanggal = implode(', ', $tanggalTerpakai);

            return redirect('/produk')->withErrors("Masih Ada Kiriman: $pesanTanggal");
        }

        // proses menghapus data
        $table = 'products';
        $result = CrudHelper::masterDeleteData($table, $id);
        return redirect('/produk')->with('success', 'Berhasil Menghapus Data');
    }
}
