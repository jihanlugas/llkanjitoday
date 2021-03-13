<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Hint;
use App\Models\Word;
use App\Models\Wordhint;
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
//        $this->middleware('request');
        $this->middleware('jwt');
        $this->middleware('auth:api');
//        $this->middleware('auth:api', ['except' => ['form']]);
    }

    public function form(Request $request){
        $request = $request->all();
        $model = Word::where('word_id', '=', $request['word_id'])->with(['hints'])->first();

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
                $Word = new Word();
                $Word->word = $request['word'];
                $Word->kana = $request['kana'];
                $Word->mean = $request['mean'];
                $Word->save();

                foreach ($request['hints'] as $key => $requesthint){
                    if ($requesthint['hint_id']){
                        $Hint = Hint::find($requesthint['hint']);
                    } else {
                        $Hint = Hint::where('hint', '=', $requesthint['hint'])->first();
                        if (empty($Hint)){
                            $Hint = new Hint();
                        }
                    }
                    $Hint->hint = $requesthint['hint'];
                    $Hint->save();

                    $Wordhint = Wordhint::where('word_id', '=', $Word->word_id)
                                    ->where('hint_id', '=', $Hint->hint_id)->first();

                    if (empty($Wordhint)){
                        $Wordhint = new Wordhint();
                        $Wordhint->word_id = $Word->word_id;
                        $Wordhint->hint_id = $Hint->hint_id;
                        $Wordhint->save();
                    }
                }

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
        $Word = Word::find($request['word_id']);
        if (empty($Word)){
            // Data Not Found
        } else {
            $rules = Word::$updateRules;
//            $rules['word'] = $rules['word'] . ',word,' . $Word->kanji_id;
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
                    $Word->word = $request['word'];
                    $Word->kana = $request['kana'];
                    $Word->mean = $request['mean'];
                    $Word->save();

                    $noremove_wordhint = [];
                    foreach ($request['hints'] as $key => $requesthint){
                        if ($requesthint['hint_id']){
                            $Hint = Hint::find($requesthint['hint_id']);
                        } else {
                            $Hint = Hint::where('hint', '=', $requesthint['hint'])->first();
                            if (empty($Hint)){
                                $Hint = new Hint();
                            }
                        }
                        $Hint->hint = $requesthint['hint'];
                        $Hint->save();

                        $Wordhint = Wordhint::where('word_id', '=', $Word->word_id)
                            ->where('hint_id', '=', $Hint->hint_id)->first();

                        if (empty($Wordhint)){
                            $Wordhint = new Wordhint();
                            $Wordhint->word_id = $Word->word_id;
                            $Wordhint->hint_id = $Hint->hint_id;
                            $Wordhint->save();
                        }

                        array_push($noremove_wordhint, $Wordhint->hint_id);
                    }

                    Wordhint::where('word_id', '=', $Word->word_id)
                        ->whereNotIn('hint_id', $noremove_wordhint)
                        ->delete();

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
