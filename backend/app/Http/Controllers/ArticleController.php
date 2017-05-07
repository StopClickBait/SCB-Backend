<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/*
	GET	/articles	index	articles.index
	GET	/articles/create	create	articles.create
	POST	/articles	store	articles.store
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
        $articles = Article::all();
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
    public function store(Request $request)
    {
        $article = new Article();
		
		$article->nameURI = $request['nameURI'];
		$article->isDeleted = 0;
		$article->userID = $request['userID'];
		
		if($article->save()){
			return $article->id;
		}
		return 'error';
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
		return 'Show method not yet implemented';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        //
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
    public function destroy(Article $article)
    {
        //
		return 'Destroy method not yet implemented';
    }
}
