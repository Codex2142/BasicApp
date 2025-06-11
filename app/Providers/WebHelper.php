<?php

namespace App\Providers;

use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class WebHelper extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

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
}
