<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kanji extends Model
{

    protected $primaryKey = 'kanji_id';

    const JLPT_N1 = 1;
    const JLPT_N2 = 2;
    const JLPT_N3 = 3;
    const JLPT_N4 = 4;
    const JLPT_N5 = 5;

    protected $fillable = [
        'word', 'strokes', 'jlpt'
    ];

    public static $createRules = [
        'kanjiId' => 'numeric',
        'word' => 'required|string|unique:kanjis',
        'strokes' => 'numeric',
        'jlpt' => 'string',
        'kanjiyomis' => 'array',
        'kanjiyomis.*.kanjiyomiId' => 'numeric',
        'kanjiyomis.*.kanjiId' => 'numeric',
        'kanjiyomis.*.word' => 'string',
        'kanjiyomis.*.type' => 'string',
        'kanjimeans' => 'array',
        'kanjimeans.*.kanjimeanId' => 'numeric',
        'kanjimeans.*.kanjiId' => 'numeric',
        'kanjimeans.*.mean' => 'string',
    ];

    public static $updateRules = [
        'kanjiId' => 'numeric',
//        'word' => 'required|string|unique:kanjis',
        'word' => 'required|string',
        'strokes' => 'numeric',
        'jlpt' => 'string',
        'kanjiyomis' => 'array',
        'kanjiyomis.*.kanjiyomiId' => 'numeric',
        'kanjiyomis.*.kanjiId' => 'numeric',
        'kanjiyomis.*.word' => 'string',
        'kanjiyomis.*.type' => 'string',
        'kanjimeans' => 'array',
        'kanjimeans.*.kanjimeanId' => 'numeric',
        'kanjimeans.*.kanjiId' => 'numeric',
        'kanjimeans.*.mean' => 'string',
    ];


    public function kanjiyomis()
    {
        return $this->hasMany(Kanjiyomi::class, 'kanji_id');
    }

    public function kanjimeans()
    {
        return $this->hasMany(Kanjimean::class, 'kanji_id');
    }


}
