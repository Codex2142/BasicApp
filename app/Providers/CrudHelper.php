<?php

namespace App\Providers;

use Illuminate\Support\Str;
use App\Rules\dataValidator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;

class CrudHelper extends ServiceProvider
{
    // untuk mendapatkan Model untuk =  Kolom CRUD / peraturan / pesan jika dilanggar
    public static function table(string $table){

        // mendapatkan alamat Model
        $className = 'App\\Models\\' . Str::studly(Str::singular($table));
        $model = $className;

        $data['fillable'] = (new $model)->getFillable();
        $data['rules'] = $model::$rules ?? [];
        $data['messages'] = $model::$messages ?? [];

        return $data;
    }

    // Menampilkan data kedalam Array
    public static function masterShowData($table, $data){
        $data = DB::table($table)->get()->toArray();
        return $data;
    }

    // Melakukan Insert Data
    public static function masterInsertData($table, $data, $input){
        $insert = $input;

        // Membuat data baru yang disusun untuk rule
        $rulesAndMessages = CrudHelper::ruleConstruct($data['fillable'], $data['rules'], $data['messages']);

        // mendefinisikan variabel
        $rules = $rulesAndMessages['rules'];
        $messages = $rulesAndMessages['messages'];

        // melakukan validasi
        $validatedData = dataValidator::masterValidator($insert, $rules, $messages);

        // jika kolom = password, maka wajib hashing
        if(isset($insert['password'])) {
            $insert['password'] = Hash::make($insert['password']);
        }

        // jika berhasil menyimpan data
        if ($validatedData === true) {
            DB::table($table)->insert($insert);
            $success = ['success' => 'Berhasil Menyimpan Data'];
            return $success;

        // jika error
        } else {
            $error =  ['error' => $validatedData];
            return $error ;
        }
    }


    public static function masterUpdateData($table, $data, $input, $id){
        $insert = $input;

        // Membuat data baru yang disusun untuk rule
        $rulesAndMessages = CrudHelper::ruleConstruct($data['fillable'], $data['rules'], $data['messages']);

        // mendefinisikan variabel
        $rules = $rulesAndMessages['rules'];
        $messages = $rulesAndMessages['messages'];

        // melakukan validasi
        $validatedData = dataValidator::masterValidator($insert, $rules, $messages);

        // jika kolom = password, maka wajib hashing
        if(isset($insert['password'])) {
            $insert['password'] = Hash::make($insert['password']);
        }

        // jika berhasil update data
        if ($validatedData === true) {
            DB::table($table)->where('id', $id)->update($insert);
            $success = ['success' => 'Berhasil Mengubah Data'];
            return $success;

        // jika error
        } else {
            $error =  ['error' => $validatedData];
            return $error ;
        }
    }

    // menghapus data
    public static function masterDeleteData($table, $id){
        try {
            $delete = DB::table($table)->where('id', $id)->delete();
            if ($delete) {

                // jika berhasil
                $success = ['success' => 'Berhasil Menghapus Data'];
                return $success;
            } else {

                // jika data tidak ada
                $error = ['error' => 'Data Tidak Ditemukan'];
                return $error;
            }

        } catch (\Exception $e) {

            // jika error lain
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

