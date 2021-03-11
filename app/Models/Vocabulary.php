<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocabulary extends Model
{
    protected $table = 'vocabularys';
    protected $primaryKey = 'vocabulary_id';

    protected $fillable = [
        'vocabulary', 'kana', 'mean'
    ];

    public static $createRules = [
        'vocabulary_id' => 'numeric',
        'vocabulary' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
    ];

    public static $updateRules = [
        'vocabulary_id' => 'numeric',
        'vocabulary' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
    ];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'wordvocabularys', 'vocabulary_id', 'word_id');
    }
}