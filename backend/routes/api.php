<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/answers/{articleId}', function ($articleId) {
    $answers = array (1=>'asd',2=>'some answer',3=>'wow article');

    return json_encode($answers);
});

Route::get('/answers/{answerId}/votes', function ($answerId) {
    $votes = 1000;
	
    return json_encode($votes); 
});

Route::get('/articles/', function () {
   $articles = array ('articles.com/articles/1', 'articles.com/articles/5', 'articles.com/articles/123', 'articles.com/articles/4000');
	
    return json_encode($articles); 
});

Route::post('/article', function(Request $request){
	$answer = $request->input('answer');
	$answerId = $request->input('articleId');
	$userId = $request->input('userId');
	
	// Create article
	// Add answer to db
	
	return 'thanks';
});

Route::post('/vote', function(Request $request){
	$answer = $request->input('answerId');
	$answerId = $request->input('isUpVote');
	$userId = $request->input('userId');
	
	// Create article
	// Add answer to db
	
	return 'thanks';
});