<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\ValidationRule;

class dataValidator implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    // melakukan validasi input dengan rule
    public static function masterValidator($input, $rule, $message)
    {
        $validator = Validator::make($input, $rule, $message);

        // jika error
        if ($validator->fails()) {
            return $validator->errors();
        }

        // jika berhasil
        return true;
    }

    public static function isEmptyJson($input)
    {
        // Coba decode input JSON
        $data = json_decode($input, true);

        // Jika gagal decode atau hasilnya bukan array, anggap tidak valid
        if (!is_array($data)) {
            return true;
        }

        // Validasi kondisi kosong
        $itemsEmpty = !isset($data['items']) || !is_array($data['items']) || count($data['items']) === 0;
        $totalZero = !isset($data['total']) || $data['total'] == 0;

        return $itemsEmpty || $totalZero;
    }
}
