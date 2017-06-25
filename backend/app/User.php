<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $guarded = array('id');

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function articles()
    {
        return $this->belongsToMany('App\Article', 'user_articles', 'userID', 'articleID')
                    ->withTimestamps();
    }
}
