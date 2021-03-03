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
        'word' => 'required|unique:kanjis|string',
        'strokes' => 'numeric',
        'jlpt' => 'numeric',
        'kanjionyomis' => 'array',
        'kanjionyomis.*.kanjionyomiId' => 'numeric',
        'kanjionyomis.*.kanjiId' => 'numeric',
        'kanjionyomis.*.word' => 'string',
        'kanjionyomis.*.type' => 'numeric',
        'kanjikunyomis' => 'array',
        'kanjikunyomis.*.kanjikunyomiId' => 'numeric',
        'kanjikunyomis.*.kanjiId' => 'numeric',
        'kanjikunyomis.*.word' => 'string',
        'kanjikunyomis.*.type' => 'numeric',
        'kanjimeans' => 'array',
        'kanjimeans.*.kanjimeanId' => 'numeric',
        'kanjimeans.*.kanjiId' => 'numeric',
        'kanjimeans.*.mean' => 'string',
    ];

    public function kanjionyomis()
    {
        return $this->hasMany(Kanjionyomi::class, 'kanji_id');
    }

    public function kanjikunyomis()
    {
        return $this->hasMany(Kanjikunyomi::class, 'kanji_id');
    }

    public function kanjimeans()
    {
        return $this->hasMany(Kanjimean::class, 'kanji_id');
    }


}
