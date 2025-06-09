<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Nama tabel
    protected $table = 'products';

    // kolom CRUD
    protected $fillable = [
        'name',
        'price1',
        'price2',
        'photo',
        'description',
    ];

    // kolom database => kolom tampilan tabel
    public static function Labelling()
    {
        return [
            'name' => 'Nama Produk',
            'price1' => 'Harga Pasar',
            'price2' => 'Harga Grosir',
            'photo' => 'Foto',
            'description' => 'Deskripsi',
        ];
    }

    // Peraturan kolom database => peraturannya
    public static $rules = [
        'name' => 'required|max:50',
        'price1' => 'required|integer',
        'price2' => 'required|integer',
        'photo' => 'nullable',
        'description' => 'nullable|max:255',
    ];

    // jika melanggar aturan => menampilkan pesan
    public static $messages = [
        'name.required' => 'Nama wajib diisi!',
        'name.max' => 'Nama maksimal 50 karakter!',

        'price1.required' => 'Harga Beli wajib diisi!',
        'price1.integer' => 'Harga Beli harus berupa angka!',

        'price2.required' => 'Harga Grosir wajib diisi!',
        'price2.integer' => 'Harga Grosir harus berupa angka!',

        'description.max' => 'Deskripsi maksimal 255 karakter!',
    ];

}
