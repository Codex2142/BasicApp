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
    public static function masterValidator($input, $rule, $message){
        $validator = Validator::make($input, $rule, $message);

        // jika error
        if($validator->fails()){
            return $validator->errors();
        }

        // jika berhasil
        return true;
    }
}
