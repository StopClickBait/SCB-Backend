<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTags extends Model
{
    //
	
	protected $fillable = ['tagID', 'userID' , 'articleID'];
}
