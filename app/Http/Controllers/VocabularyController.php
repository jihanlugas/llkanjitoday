<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Vocabulary;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;


class VocabularyController extends Controller
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
//        $this->middleware('auth:api');
//        $this->middleware('auth:api', ['except' => ['form']]);
    }

    public function form(Request $request){
        $request = $request->all();
        $model = Vocabulary::where('vocabulary_id', '=', $request['vocabulary_id'])->with(['words'])->first();

        $payload = [
            'success' => true,
            'data' => $model
        ];

        return $this->response($payload, 200);
//        return $this->responseWithoutToken(Response::success($model));
    }

    public function store(Request $request){
        $request = $request->all();
        $rules = Vocabulary::$createRules;
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
                $Vocabulary = new Vocabulary();
                $Vocabulary->vocabulary = $request['vocabulary'];
                $Vocabulary->kana = $request['kana'];
                $Vocabulary->mean = $request['mean'];
                $Vocabulary->save();

//                foreach ($request['hints'] as $key => $requesthint){
//                    if ($requesthint['hint_id']){
//                        $Hint = Hint::find($requesthint['hint']);
//                    } else {
//                        $Hint = Hint::where('hint', '=', $requesthint['hint'])->first();
//                        if (empty($Hint)){
//                            $Hint = new Hint();
//                        }
//                    }
//                    $Hint->hint = $requesthint['hint'];
//                    $Hint->save();
//
//                    $Vocabularyhint = Vocabularyhint::where('vocabulary_id', '=', $Vocabulary->vocabulary_id)
//                                    ->where('hint_id', '=', $Hint->hint_id)->first();
//
//                    if (empty($Vocabularyhint)){
//                        $Vocabularyhint = new Vocabularyhint();
//                        $Vocabularyhint->vocabulary_id = $Vocabulary->vocabulary_id;
//                        $Vocabularyhint->hint_id = $Hint->hint_id;
//                        $Vocabularyhint->save();
//                    }
//                }

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
        $Vocabulary = Vocabulary::find($request['vocabulary_id']);
        if (empty($Vocabulary)){
            // Data Not Found
        } else {
            $rules = Vocabulary::$updateRules;
//            $rules['vocabulary'] = $rules['vocabulary'] . ',vocabulary,' . $Vocabulary->kanji_id;
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
                    $Vocabulary->vocabulary = $request['vocabulary'];
                    $Vocabulary->kana = $request['kana'];
                    $Vocabulary->mean = $request['mean'];
                    $Vocabulary->save();

//                    $noremove_vocabularyhint = [];
//                    foreach ($request['hints'] as $key => $requesthint){
//                        if ($requesthint['hint_id']){
//                            $Hint = Hint::find($requesthint['hint_id']);
//                        } else {
//                            $Hint = Hint::where('hint', '=', $requesthint['hint'])->first();
//                            if (empty($Hint)){
//                                $Hint = new Hint();
//                            }
//                        }
//                        $Hint->hint = $requesthint['hint'];
//                        $Hint->save();
//
//                        $Vocabularyhint = Vocabularyhint::where('vocabulary_id', '=', $Vocabulary->vocabulary_id)
//                            ->where('hint_id', '=', $Hint->hint_id)->first();
//
//                        if (empty($Vocabularyhint)){
//                            $Vocabularyhint = new Vocabularyhint();
//                            $Vocabularyhint->vocabulary_id = $Vocabulary->vocabulary_id;
//                            $Vocabularyhint->hint_id = $Hint->hint_id;
//                            $Vocabularyhint->save();
//                        }
//
//                        array_push($noremove_vocabularyhint, $Vocabularyhint->hint_id);
//                    }
//
//                    Vocabularyhint::where('vocabulary_id', '=', $Vocabulary->vocabulary_id)
//                        ->whereNotIn('hint_id', $noremove_vocabularyhint)
//                        ->delete();

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
