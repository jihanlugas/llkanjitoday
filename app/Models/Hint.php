<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hint extends Model
{

    protected $primaryKey = 'hint_id';

    protected $fillable = [
        'hint'
    ];

    public static $createRules = [
        'hint_id' => 'numeric',
        'hint' => 'string',
    ];

    public static $updateRules = [
        'hint_id' => 'numeric',
        'hint' => 'string',
    ];

    public function words()
    {
        return $this->belongsToMany(Word::class, 'wordhints', 'hint_id', 'word_id');
    }

    public function vocabulary()
    {
        return $this->belongsToMany(Vocabulary::class, 'vocabularyhints', 'hint_id', 'vocabulary_id');
    }
}
