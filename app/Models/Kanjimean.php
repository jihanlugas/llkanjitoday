<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kanjimean extends Model
{

    protected $primaryKey = 'kanjimean_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'kanji_id', 'mean'
    ];

//    public function comments()
//    {
//        return $this->hasMany(Comment::class);
//    }


}
