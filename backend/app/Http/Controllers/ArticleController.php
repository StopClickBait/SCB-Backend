<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Article;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
	GET	/articles	index	articles.index
	GET	/articles/create	create	articles.create
	POST/articles	store	articles.store
	GET	/articles/{article}	show	articles.show
	GET	/articles/{article}/edit	edit	articles.edit
	PUT/PATCH	/articles/{article}	update	articles.update
	DELETE	/articles/{article}	destroy	articles.destroy
*/
class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::where('isDeleted', 0)
			->get();
		return $articles;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		return 'Create method not yet implemented';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //// 500 SERVER ERROR:
    public function store(Request $request)
    {
        // $article = new Article();
		
		// $existingArticle = DB::table('articles')
		// 	->where('nameURI', $request['nameURI'])
		// 	->where('isDeleted',0)
		// 	->get();
		// if(!$existingArticle->isEmpty()){
		// 	return 'exists';
		// }
		// else {
		// 	$article->nameURI = $request['nameURI'];
		// 	$article->isDeleted = 0;
		// 	$article->userID = $request['userID'];
			
		// 	if($article->save()){
		// 		return $article->id;
		// 	}
		// }
		// return 'error';
        return 'store not working properly';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
		return $article;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
		return 'Edit method not yet implemented';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        //

		return 'Update method not yet implemented';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
     // ERRORS:
    public function destroy(Article $article)
    {
        //return $article->delete();
        return 'destroy not yet working';
    }

    public function UserArticles(User $user)
    {
        $articles = $user->articles;
        return $articles;
    }
}
