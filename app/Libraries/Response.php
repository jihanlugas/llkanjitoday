<?php

namespace App\Libraries;

use Illuminate\Support\Str;

class Response {
    public static function success($payload = []){
        $return = [
            "success" => true,
            "message" => "Request Success",
            "payload" => Helpers::keyCamel($payload)
        ];
        return $return;
    }

//    public static function keySnake($data){
//        $return = [];
//
//        if ($data instanceof \Illuminate\Database\Eloquent\Model)
//            $data = $data->getAttributes();
//
//        foreach ($data as $key => $value){
//            if (is_array($value)){
//                $return[Str::snake($key)] = Helpers::keySnake($value);
//            } else if ($value instanceof \Illuminate\Database\Eloquent\Collection){
//                $return[Str::snake($key)] = Helpers::keySnake($value);
//            } else if ($value instanceof \Illuminate\Database\Eloquent\Model){
//                $return[Str::snake($key)] = Helpers::keySnake($value);
//            } else {
//                $return[Str::snake($key)] = $value;
//            }
//        }
//
//        return $return;
//    }
}
