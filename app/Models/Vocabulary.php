<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $table = 'vocabularys';
    protected $primaryKey = 'vocabulary_id';

    protected $fillable = [
        'vocabulary', 'kana', 'mean', 'notes'
    ];

    public static $createRules = [
        'vocabulary_id' => 'numeric',
        'vocabulary' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
        'notes' => 'string',
    ];

    public static $updateRules = [
        'vocabulary_id' => 'numeric',
        'vocabulary' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
        'notes' => 'string',
    ];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'wordvocabularys', 'vocabulary_id', 'word_id');
    }

    public function hints()
    {
        return $this->belongsToMany(Hint::class, 'vocabularyhints', 'vocabulary_id', 'hint_id');
    }
}
