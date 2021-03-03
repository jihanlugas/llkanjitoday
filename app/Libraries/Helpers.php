<?php

namespace App\Libraries;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class Helpers {

    // handle data type model to get attributes and relation
    private static function handleModel($model){
        $data = $model->getAttributes();
        foreach ($model->getRelations() as $key => $relation){
            $data[$key] = $relation;
        }
        return $data;
    }

    // handle data type pagination to get pagination attributes
    private static function handlePaginate(LengthAwarePaginator $data){
        $return['total'] = $data->total();
        $return['lastPage'] = $data->lastPage();
        $return['items'] = $data->items();
        $return['perPage'] = $data->perPage();
        $return['currentPage'] = $data->currentPage();

        return $return;
    }

    public static function keyCamel($data){
        $return = [];

        if ($data instanceof LengthAwarePaginator){
            $data = self::handlePaginate($data);
        }

        if ($data instanceof \Illuminate\Database\Eloquent\Model){
            $data = self::handleModel($data);
        }

        foreach ($data as $key => $value){
            if (is_array($value)){
                $return[Str::camel($key)] = Helpers::keyCamel($value);
            } else if ($value instanceof \Illuminate\Database\Eloquent\Collection){
                $return[Str::camel($key)] = Helpers::keyCamel($value);
            } else if ($value instanceof \Illuminate\Database\Eloquent\Model){
                $return[Str::camel($key)] = Helpers::keyCamel($value);
            } else {
                $return[Str::camel($key)] = $value;
            }
        }

        return $return;
    }

    public static function keySnake($data){
        $return = [];

        if ($data instanceof \Illuminate\Database\Eloquent\Model)
            $data = $data->getAttributes();

        foreach ($data as $key => $value){
            if (is_array($value)){
                $return[Str::snake($key)] = Helpers::keySnake($value);
            } else if ($value instanceof \Illuminate\Database\Eloquent\Collection){
                $return[Str::snake($key)] = Helpers::keySnake($value);
            } else if ($value instanceof \Illuminate\Database\Eloquent\Model){
                $return[Str::snake($key)] = Helpers::keySnake($value);
            } else {
                $return[Str::snake($key)] = $value;
            }
        }

        return $return;
    }
}
