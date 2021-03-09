<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{

    protected $primaryKey = 'word_id';

    protected $fillable = [
        'word', 'kana', 'mean'
    ];

    public static $createRules = [
        'word_id' => 'numeric',
        'word' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
    ];

    public static $updateRules = [
        'word_id' => 'numeric',
        'word' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
    ];

    public function hints()
    {
        return $this->belongsToMany(Hint::class, 'wordhints', 'word_id', 'hint_id');
    }
}
