<?php

namespace App\Providers;

use Illuminate\Support\Str;
use App\Rules\dataValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class CrudHelper extends ServiceProvider
{
    public static function table(string $table){
        $className = 'App\\Models\\' . Str::studly(Str::singular($table));
        $model = $className;

        $data['fillable'] = (new $model)->getFillable();
        $data['rules'] = $model::$rules ?? [];
        $data['messages'] = $model::$messages ?? [];

        return $data;
    }


    public static function masterShowData($table, $data){
        $data = DB::table($table)->get()->toArray();
        return $data;
    }


    public static function masterInsertData($table, $data, $input){
        $insert = $input;
        $rulesAndMessages = CrudHelper::ruleConstruct($data['fillable'], $data['rules'], $data['messages']);

        $rules = $rulesAndMessages['rules'];
        $messages = $rulesAndMessages['messages'];

        $validatedData = dataValidator::masterValidator($insert, $rules, $messages);
        if ($validatedData === true) {
            DB::table($table)->insert($insert);
            $success = ['success' => 'Berhasil Menyimpan Data'];
            return $success;
        } else {
            $error =  ['error' => $validatedData];
            return $error ;
        }
    }


    public static function masterUpdateData($table, $data, $input, $id){
        $insert = $input;
        $rulesAndMessages = CrudHelper::ruleConstruct($data['fillable'], $data['rules'], $data['messages']);

        $rules = $rulesAndMessages['rules'];
        $messages = $rulesAndMessages['messages'];

        $validatedData = dataValidator::masterValidator($insert, $rules, $messages);

        if ($validatedData === true) {
            DB::table($table)->where('id', $id)->update($insert);
            $success = ['success' => 'Berhasil Mengubah Data'];
            return $success;
        } else {
            $error =  ['error' => $validatedData];
            return $error ;
        }
    }

    public static function masterDeleteData($table, $id){
        try {
            $delete = DB::table($table)->where('id', $id)->delete();
            if ($delete) {
                $success = ['success' => 'Berhasil Menghapus Data'];
                return $success;
            } else {
                $error = ['error' => 'Data Tidak Ditemukan'];
                return $error;
            }

        } catch (\Exception $e) {
            $error = ['error' => 'Terjadi Kesalahan Saat Menghapus'];
            return $error;
        }
    }

    // Menyusun Rule yang akan digunakan
    public static function ruleConstruct($fill, $rule, $messages)
    {
        $rules = [];
        $msgs = [];
        foreach ($fill as $col) {
            if (isset($rule[$col])) {
                $rules[$col] = $rule[$col];

                foreach ($messages as $key => $msg) {
                    if (str_starts_with($key, $col . '.')) {
                        $msgs[$key] = $msg;
                    }
                }
            }
        }

        return [
            'rules' => $rules,
            'messages' => $msgs,
        ];
    }

}

