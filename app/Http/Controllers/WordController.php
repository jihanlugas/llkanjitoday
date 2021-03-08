<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Word;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;


class WordController extends Controller
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

    public function form(Request $request){
        $request = $request->all();
        $model = Word::where('word_id', '=', $request['word_id'])->first();

        $payload = [
            'success' => true,
            'data' => $model
        ];

        return $this->response($payload, 200);
//        return $this->responseWithoutToken(Response::success($model));
    }

    public function store(Request $request){
        $request = $request->all();
        $rules = Word::$createRules;
        $validator = Validator::make($request, $rules);

        if ($validator->errors()->messages()){
            $payload = [
                'errors' => [
                    'message' => 'Validated',
                    'code' => 400,
                    'validate' => $validator->errors()->messages()
                ]
            ];
            return $this->response($payload, 400);
//            return $this->responseWithoutToken(Response::validation($validator->errors()->messages()));
        } else {
            DB::beginTransaction();
            try {
                $word = new Word();
                $word->word = $request['word'];
                $word->kana = $request['kana'];
                $word->mean = $request['mean'];
                $word->save();

                $payload = [
                    'success' => true,
                    'message' => 'Data Saved'
                ];

                DB::commit();
                return $this->response($payload, 201);
            } catch (Throwable $e) {
                DB::rollBack();
                dd($e);
            }
        }
    }

    public function update(Request $request){
        $request = $request->all();
        $word = Word::find($request['word_id']);
        if (empty($word)){
            // Data Not Found
        } else {
            $rules = Word::$updateRules;
//            $rules['word'] = $rules['word'] . ',word,' . $word->kanji_id;
            $validator = Validator::make($request, $rules);
            if ($validator->errors()->messages()){
                $payload = [
                    'errors' => [
                        'message' => 'Validated',
                        'code' => 400,
                        'validate' => $validator->errors()->messages()
                    ]
                ];
                return $this->response($payload, 400);
//                return $this->responseWithoutToken(Response::validation($validator->errors()->messages()));
            } else {
                DB::beginTransaction();
                try {
                    $word->word = $request['word'];
                    $word->kana = $request['kana'];
                    $word->mean = $request['mean'];
                    $word->save();

                    $payload = [
                        'success' => true,
                        'message' => 'Data Saved'
                    ];

                    DB::commit();
                    return $this->response($payload, 201);
                } catch (Throwable $e) {
                    DB::rollBack();
                    dd($e);
                }
            }
        }
    }



//    private function paginate(Builder $query){
//        $data = $query->paginate(10);
//
//        return $data;
//
//    }
}
