<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kanjionyomi extends Model
{

    protected $primaryKey = 'kanjionyomi_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'kanji_id', 'word', 'type'
    ];

//    public function comments()
//    {
//        return $this->hasMany(Comment::class);
//    }


}
