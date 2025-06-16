<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
\Carbon\Carbon::setLocale('id');
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class WebHelper extends ServiceProvider
{
    // Mengubah Products = product
    public static function removeWord($word)
    {
        $word = Str::lower($word);
        if (preg_match('/(ses|shes|ches|xes|zes)$/i', $word)) {
            return substr($word, 0, -2);
        }

        if (preg_match('/s$/i', $word)) {
            return substr($word, 0, -1);
        }

        return $word;
    }

    // 22-12-2025 menjadi 22 Des 2025 or 22 Des 2025
    public static function dateIndonesia($date, ?string $source = null)
    {
        if ($source) {
            Carbon::setLocale('id');
            $data = Carbon::parse($date)->translatedFormat('H:i d M Y');
            return $data;
        } else {
            Carbon::setLocale('id');
            $data = Carbon::parse($date)->translatedFormat('d M Y');
            return $data;
        }
    }

    // mendapatkan bulan saat ini MM
    public static function getCurrentMonth()
    {
        Carbon::setLocale('id');
        $data = Carbon::now()->translatedFormat('F');
        return $data;
    }

    // melakukan format log
    public static function logFormatter($data)
    {
        $formatted = [];
        foreach ($data as $d) {
            $user = User::find($d->causer_id);
            $entry = [
                'user_id' => $d->causer_id,
                'username' => $user->username,
                'tipe' => $d->log_name,
                'deskripsi' => $d->description,
                'waktu' => self::dateIndonesia($d->created_at, 'log'),
            ];

            // Tambah detail properties untuk insert/update/delete
            if (in_array($d->log_name, ['insert', 'update', 'delete'])) {
                $props = json_decode($d->properties, true); // ubah jadi array

                // Cek apakah ada key 'data' dan 'tanggal'
                if (isset($props['data']['tanggal'])) {
                    $props['data']['tanggal'] = self::dateIndonesia($props['data']['tanggal']);
                }

                $entry['detail'] = $props;
            }

            $formatted[] = $entry;
        }
        return $formatted;
    }

    // mengubat int ke format rupiah
    public static function integerToRupiah($data, $withSymbol = true, $decimal = 0)
    {
        if (!is_numeric($data)) {
            return $withSymbol ? 'Rp 0' : '0';
        }

        $formatted = number_format((float) $data, $decimal, ',', '.');
        return $withSymbol ? 'Rp ' . $formatted : $formatted;
    }

    // mengubah json menjadi array
    public static function jsonToArray($invoices)
    {
        $formatted = [];

        foreach ($invoices as $invoice) {
            $products = json_decode($invoice['product'], true);

            foreach ($products['items'] as $product) {
                $formatted[] = [
                    'id' => $product['id'],
                    'tanggal' => WebHelper::dateIndonesia($invoice['tanggal']),
                    'product' => $product['name'],
                    'qty' => $product['qty'],
                    'subtotal' => $product['subtotal'],
                    'total' => $invoice['total'],
                    'description' => $invoice['description'],
                    'type' => $invoice['type'],
                    'idInvoice' => $invoice['id'],
                ];
            }
        }
        return $formatted;
    }

    public static function summaryProduct($year, $month)
    {
        $invoices = Invoice::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get()->toArray();

        $transactions = Transaction::whereYear('tanggal', $year)->whereMonth('tanggal', $month)->get()->toArray();

        $formatted = [];

        // Proses invoices
        foreach ($invoices as $inv) {
            $productData = json_decode($inv['product'], true);

            if (json_last_error() === JSON_ERROR_NONE && isset($productData['items'])) {
                foreach ($productData['items'] as $item) {
                    $productId = $item['id'];

                    if (!isset($formatted[$productId])) {
                        $formatted[$productId] = [
                            'id' => $productId,
                            'name' => $item['name'] ?? 'Unknown Product',
                            'qty' => 0,
                        ];
                    }
                    $formatted[$productId]['qty'] += $item['qty'];
                }
            }
        }

        // Proses transactions
        foreach ($transactions as $tran) {
            $productData = json_decode($tran['product'], true);

            if (json_last_error() === JSON_ERROR_NONE && isset($productData['items'])) {
                foreach ($productData['items'] as $item) {
                    $productId = $item['id'];

                    if (!isset($formatted[$productId])) {
                        $formatted[$productId] = [
                            'id' => $productId,
                            'name' => $item['name'] ?? 'Unknown Product',
                            'qty' => 0,
                        ];
                    }
                    $formatted[$productId]['qty'] += $item['qty'];
                }
            }
        }

        // 10 terbanyak
        usort($formatted, function ($a, $b) {
            return $b['qty'] - $a['qty'];
        });

        return [
            'year' => $year,
            'month' => $month,
            'products' => array_slice(array_values($formatted), 0, 10), // Ambil 10 teratas
            'total_products' => count($formatted)
        ];
    }
}
