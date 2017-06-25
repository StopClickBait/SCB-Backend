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

// Information related to users:
Route::get('/users/{user}/articles', 'ArticleController@UserArticles');
Route::get('/users/{user}/posts', 'PostsController@UserPosts');
Route::resource('users', 'UserController');

// Information related to posts:
Route::get('/posts/{post}/votes', 'PostsController@Votes');
Route::resource('posts', 'PostsController');

// Information related to users:
Route::get('/articles/{article}/users', 'UserController@ArticleUsers');
Route::get('/articles/{article}/posts', 'PostsController@ArticlePosts');
Route::resource('articles', 'ArticleController');


Route::post('/vote', function(Request $request){
	$answer = $request->input('answerId');
	$answerId = $request->input('isUpVote');
	$userId = $request->input('userId');
	
	// Create article
	// Add answer to db
	
	return 'thanks';
});

Route::resource('article.tags', 'ArticleTagController', [
				'article'=>'articleId', 'tagId'=>'atID'
]);
