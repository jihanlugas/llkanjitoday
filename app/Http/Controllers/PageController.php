<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Kanji;
use App\Models\User;
use App\Models\Word;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use MongoDB\Driver\Query;
use Symfony\Component\HttpFoundation\Cookie;


class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('cors');
        $this->middleware('request');
        $this->middleware('jwt');
        $this->middleware('auth:api');
    }

    public function kanji(){
        $query = Kanji::query();
        $query->with(['kanjiyomis', 'kanjimeans']);
        $data = $this->paginate($query);

        $payload = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response($payload, 200);
    }

    public function word(){
        $query = Word::query();
//        $query->with(['kanjiyomis', 'kanjimeans']);
        $data = $this->paginate($query);

        $payload = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response($payload, 200);
    }

    private function paginate(Builder $query){
        $data = $query->paginate(10);

        return $data;

    }
}
