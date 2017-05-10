<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    //
	
	protected $fillable = ['tagID', 'userID' , 'articleID'];
}
