<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Kanji;
use App\Models\Kanjiyomi;
use App\Models\Kanjimean;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;


class KanjiController extends Controller
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
    }

    public function form(Request $request){
        $request = $request->all();
        $model = Kanji::where('kanji_id', '=', $request['kanji_id'])->with(['kanjiyomis', 'kanjimeans'])->first();
        $payload = [
            'success' => true,
            'data' => $model
        ];

        return $this->response($payload, 200);
//        return $this->responseWithoutToken(Response::success($model));
    }

    public function store(Request $request){
        $request = $request->all();
        $rules = Kanji::$createRules;
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
                $kanji = new Kanji();
                $kanji->word = $request['word'];
                $kanji->strokes = $request['strokes'];
                $kanji->jlpt = $request['jlpt'];
                $kanji->save();

                foreach ($request['kanjiyomis'] as $onyomi){
                    $kanjiyomi = new Kanjiyomi();
                    $kanjiyomi->kanji_id = $kanji->kanji_id;
                    $kanjiyomi->word = $onyomi['word'];
                    $kanjiyomi->type = $onyomi['type'];
                    $kanjiyomi->save();
                }

                foreach ($request['kanjimeans'] as $mean){
                    $kanjimean = new Kanjimean();
                    $kanjimean->kanji_id = $kanji->kanji_id;
                    $kanjimean->mean = $mean['mean'];
                    $kanjimean->save();
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
        $kanji = Kanji::find($request['kanji_id']);
        if (empty($kanji)){
            // Data Not Found
        } else {
            $rules = Kanji::$updateRules;
//            $rules['word'] = $rules['word'] . ',word,' . $kanji->kanji_id;
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
                    $kanji->word = $request['word'];
                    $kanji->strokes = $request['strokes'];
                    $kanji->jlpt = $request['jlpt'];
                    $kanji->save();

                    $noremove_yomi = [];
                    foreach ($request['kanjiyomis'] as $onyomi){
                        if ($onyomi['kanjiyomi_id']){
                            $kanjiyomi = Kanjiyomi::find($onyomi['kanjiyomi_id']);
                        } else {
                            $kanjiyomi = new Kanjiyomi();
                        }
                        $kanjiyomi->kanji_id = $kanji->kanji_id;
                        $kanjiyomi->word = $onyomi['word'];
                        $kanjiyomi->type = $onyomi['type'];
                        $kanjiyomi->save();
                        array_push($noremove_yomi, $kanjiyomi->kanjiyomi_id);
                    }

                    Kanjiyomi::where('kanji_id', '=', $kanji->kanji_id)
                        ->whereNotIn('kanjiyomi_id', $noremove_yomi)
                        ->delete();


                    $noremove_mean = [];
                    foreach ($request['kanjimeans'] as $mean){
                        if ($mean['kanjimean_id']){
                            $kanjimean = Kanjimean::find($mean['kanjimean_id']);
                        } else {
                            $kanjimean = new Kanjimean();
                        }
                        $kanjimean->kanji_id = $kanji->kanji_id;
                        $kanjimean->mean = $mean['mean'];
                        $kanjimean->save();
                        array_push($noremove_mean, $kanjimean->kanjimean_id);
                    }

                    Kanjimean::where('kanji_id', '=', $kanji->kanji_id)
                        ->whereNotIn('kanjimean_id', $noremove_mean)
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
