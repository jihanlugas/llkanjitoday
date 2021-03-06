<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kanjiyomi extends Model
{

    protected $primaryKey = 'kanjiyomi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    const TYPE_ONYOMI = "ONYOMI";
    const TYPE_KUNYOMI = "KUNYOMI";

    protected $fillable = [
        'kanji_id', 'word', 'type'
    ];

//    public function comments()
//    {
//        return $this->hasMany(Comment::class);
//    }


}
