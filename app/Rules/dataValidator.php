<?php

namespace App\Rules;

use Closure;
use App\Models\Product;
use App\Models\Transaction;
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

    // Validasi Stock dari json
    public static function stockValidator($input, $product, ?string $param = null)
    {
        // ini jika transaksi > add
        if ($param == 'add') {
            // Decode jika input berupa JSON string
            $input = json_decode($input, true);

            // dd($input, $product, $param);

            foreach ($input['items'] as $item){
                foreach ($product as $prod){
                    if ($prod['id'] == $item['id']){
                        Product::where('id', $item['id'])->update([
                            'stock' => $prod['stock'] - $item['qty'],
                        ]);
                    }
                }
            }

            unset($prod); // untuk mencegah referensi rusak
            return true;

        } elseif ($param == 'edit') {

            // ini jika transaksi > edit
            if (is_string($input)) {
                $input = json_decode($input, true);
            }
            foreach ($input as $inp) {
                foreach ($product as $prod) {
                    if ($inp['id'] == $prod['id']) {
                        $prod['stock'] = $inp['updatedStock'];
                        product::where('id', $prod['id'])->update([
                            'stock' => $prod['stock'],
                        ]);
                    }
                }
            }
            unset($product);
            return true;
        }

        // ini jika transaksi > delete
        $data = Transaction::where('id', $input)->get()->toArray();
        $data = json_decode($data[0]['product'], true);

        // dd($data['items']);
        foreach ($data['items'] as $item){
            foreach ($product as $prod){
                if ($item['id'] == $prod['id']){
                    Product::where('id', $item['id'])->update([
                        'stock' => $prod['stock'] + $item['qty'],
                    ]);
                }
            }
        }
        unset($product);
        return true;
    }
}
