<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    // Untuk riwayat
    use LogsActivity;

    // Nama tabel
    protected $table = 'products';

    // kolom CRUD
    protected $fillable = [
        'name',
        'price1',
        'price2',
        'photo',
        'stock',
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
            'stock' => 'Stok',
            'description' => 'Deskripsi',
        ];
    }

    // Peraturan kolom database => peraturannya
    public static $rules = [
        'name' => 'required|max:50',
        'price1' => 'required|integer',
        'price2' => 'required|integer',
        'photo' => 'nullable',
        'stock' => 'integer|required|min:0',
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

        'stock.integer' => 'Stok harus berupa angka!',
        'stock.required' => 'Stok wajib diisi!',
        'stock.min' => 'Stok minimal 0!',

        'description.max' => 'Deskripsi maksimal 255 karakter!',
    ];

    // laravel Spatie
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price1', 'price2'])
            ->useLogName('product')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
