<?php

namespace App\Providers;

use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');
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

    public static function dateIndonesia($date)
    {
        Carbon::setLocale('id');
        $data = Carbon::parse($date)->translatedFormat('d M Y');
        return $data;
    }

    public static function getCurrentMonth()
    {
        Carbon::setLocale('id');
        $data = Carbon::now()->translatedFormat('F');
        return $data;
    }
}
