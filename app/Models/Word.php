<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{

    protected $primaryKey = 'word_id';

    protected $fillable = [
        'word', 'mean', 'description'
    ];

    public static $createRules = [
        'word_id' => 'numeric',
        'word' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
        'hint' => 'string',
    ];

    public static $updateRules = [
        'word_id' => 'numeric',
        'word' => 'required|string',
        'kana' => 'string',
        'mean' => 'string',
        'hint' => 'string',
    ];
}
