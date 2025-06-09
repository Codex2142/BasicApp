<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Nama tabel
    protected $table = 'transactions';

    // kolom CRUD
    protected $fillable = [
        'tanggal',
        'description',
        'product',
        'total',
        'status',
    ];

    // kolom database => kolom tampilan tabel
    public static function Labelling()
    {
        return [
            'tanggal' => 'Tanggal Pemesanan',
            'description' => 'Deskripsi',
            'product' => 'Produk',
            'total' => 'Total Harga',
            'status' => 'Status',
        ];
    }

    // Peraturan kolom database => peraturannya
    public static $rules = [
        'tanggal' => 'required|unique:transactions,tanggal',
        'description' => 'required|max:30',
        'product' => 'required',
        'total' => 'required|integer',
        'status' => 'required',
    ];

    // jika melanggar aturan => menampilkan pesan
    public static $messages = [
        'tanggal.required' => 'Tanggal wajib diisi!',
        'tanggal.unique' => 'Tanggal Sudah ada!',

        'product.required' => 'Pilih minimal 1 produk!',

        'description.required' => 'Deskripsi wajib diisi!',
        'description.max' => 'Deskripsi tidak boleh lebih dari 30',

        'total.required' => 'Total wajib diisi!',
        'price2.integer' => 'Total harus berupa angka!',

        'status.required' => 'Tolong pilih Status!',

    ];
}
