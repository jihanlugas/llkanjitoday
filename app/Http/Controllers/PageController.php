<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Kanji;
use App\Models\User;
use App\Models\Vocabulary;
use App\Models\Word;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
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

    public function kanji(Request $request){
        $request = $request->all();
        $query = Kanji::query();
        $query->with(['kanjiyomis', 'kanjimeans']);
        $data = $this->paginate($query, $request['per_page'] ,$request['page']);

        $payload = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response($payload, 200);
    }

    public function word(Request $request){
        $request = $request->all();
        $query = Word::query();
//        $query->with(['hints']);
        $data = $this->paginate($query, $request['per_page'] ,$request['page']);

        $payload = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response($payload, 200);
    }

    public function vocabulary(Request $request){
        $request = $request->all();
        $query = Vocabulary::query();
//        $query->with(['hints']);
        $data = $this->paginate($query, $request['per_page'] ,$request['page']);

        $payload = [
            'success' => true,
            'data' => $data,
        ];

        return $this->response($payload, 200);
    }

    private function paginate(Builder $query, $perPage = 10, $page = null){
        $columns = ['*'];
        $pageName = 'page';

        $data = $query->paginate($perPage, $columns, $pageName, $page);

        return $data;

    }
}
