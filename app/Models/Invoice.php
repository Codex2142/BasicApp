<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Invoice extends Model
{
    // Untuk riwayat
    use LogsActivity;

    // Nama tabel
    protected $table = 'invoices';

    // kolom CRUD
    protected $fillable = [
        'tanggal',
        'product',
        'total',
        'description',
        'type',
    ];

    // kolom database => kolom tampilan tabel
    public static function Labelling()
    {
        return [
            'tanggal' => 'Tanggal',
            'product' => 'Produk',
            'qty' => 'Jumlah',          // nambah sendiri
            'subtotal' => 'Subtotal',   // nambah sendiri
            'total' => 'Total',
            'description' => 'Deskripsi',
            'type' => 'Tipe',
        ];
    }

    // Peraturan kolom database => peraturannya
    public static $rules = [
        'tanggal' => 'required',
        'product' => 'required',
        'total' => 'required|integer',
        'description' => 'nullable',
        'type' => 'required',
    ];

    // jika melanggar aturan => menampilkan pesan
    public static $messages = [
        'tanggal.required' => 'Tanggal wajib diisi!',

        'product.required' => 'Minimal memilih 1 produk!',

        'total.required' => 'Total wajib diisi!',

        'type' => 'Grosir atau Retail!',
    ];

    // laravel Spatie
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['tanggal', 'product', 'total', 'description', 'type'])
            ->useLogName('invoice')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
