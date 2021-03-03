<?php

namespace App\Http\Controllers;

use App\Libraries\Helpers;
use App\Libraries\Response;
use App\Models\Kanji;
use App\Models\Kanjikunyomi;
use App\Models\Kanjimean;
use App\Models\Kanjionyomi;
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
        $this->middleware('request');
        $this->middleware('jwt');
//        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $request = Helpers::keySnake($request->all());
        $validator = Validator::make($request, Kanji::$createRules);

        if ($validator->errors()->messages()){
            return $this->responseWithoutToken(Response::success($validator->errors()->messages()));
        } else {
            DB::beginTransaction();
            try {
                $kanji = new Kanji();
                $kanji->word = $request['word'];
                $kanji->strokes = $request['strokes'];
                $kanji->jlpt = $request['jlpt'];
                $kanji->save();

                foreach ($request['kanjionyomis'] as $onyomi){
                    $kanjionyomi = new Kanjionyomi();
                    $kanjionyomi->kanji_id = $kanji->kanji_id;
                    $kanjionyomi->word = $onyomi['word'];
                    $kanjionyomi->type = $onyomi['type'];
                    $kanjionyomi->save();
                }

                foreach ($request['kanjikunyomis'] as $kunyomi){
                    $kanjikunyomi = new Kanjikunyomi();
                    $kanjikunyomi->kanji_id = $kanji->kanji_id;
                    $kanjikunyomi->word = $kunyomi['word'];
                    $kanjikunyomi->type = $kunyomi['type'];
                    $kanjikunyomi->save();
                }

                foreach ($request['kanjimeans'] as $mean){
                    $kanjimean = new Kanjimean();
                    $kanjimean->kanji_id = $kanji->kanji_id;
                    $kanjimean->mean = $mean['mean'];
                    $kanjimean->save();
                }

                $payload = [
                    'status' => true,
                    'message' => 'Data Saved'
                ];

                DB::commit();
                return $this->responseWithoutToken(Response::success($payload));
            } catch (Throwable $e) {
                DB::rollBack();
                dd($e);
            }
        }


//        return $this->responseWithoutToken(Response::success($request));
    }

    private function paginate(Builder $query){
        $data = $query->paginate(10);

        return $data;

    }
}
