<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $guarded = array('id');

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_articles', 'articleID', 'userID')
                    ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}
