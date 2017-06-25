<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = array('id');

    public function article()
    {
        return $this -> belongsTo('App\Article');
    }

    public function user()
    {
        return $this -> belongsTo('App\User');
    }
}
