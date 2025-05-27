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
            return "Data Berhasil Disimpan!";
        } else {
            return $validatedData;
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
            return "Data Berhasil Diperbarui";
        } else {
            return $validatedData;
        }
    }

    public static function masterDeleteData($table, $id){
        try {
            $delete = DB::table($table)->where('id', $id)->delete();
            if ($delete) {
                return "Data berhasil dihapus!";
            } else {
                return "Data tidak ditemukan!";
            }

        } catch (\Exception $e) {
            return "Terjadi kesalahan saat menghapus data: " . $e->getMessage();
        }
    }



    // Menyusun Data yang akan diproses
    public static function dataConstruct($fill, $value)
    {
        $data = [];
        foreach ($fill as $index => $col) {
            $data[$col] = $value[$index] ?? null;
        }

        return $data;
    }



    // // Menyusun Rule yang akan digunakan
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

