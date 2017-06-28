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
		return 'Not Implemented.';
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

        $existingArticle = Article::where('nameURI', $request['nameURI'])
            ->where('isDeleted', 0)
            ->get();
		if(!$existingArticle->isEmpty()){
			return 'exists';
		}
		else {
			$article->nameURI = $request['nameURI'];
			$article->isDeleted = 0;
			$article->userID = $request['userID'];
			if($article->save()){
				return $article;
			}
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
		return 'Not Implemented.';
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
        if($request['nameURI']) {
            $article -> nameURI = $request['nameURI'];
        }
        if($request['userID']) {
            $article -> userID = $request['userID'];
        }
        if($article->save())
        {
            return $article;
        }
        return 'error';
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
        // -> deleting an article means that all its "posts"
        //    or "comments" will not be attatched to an article.
        //    Thus, we won't use the $article->delete() function,
        //    but instead set 'isDeleted' to 1.
        $article -> isDeleted = 1;
        if($article -> save())
        {
            return $article;
        }
    }

    public function UserArticles(User $user)
    {
        $article = Article::where("userID", $user -> id) -> get();
        return $article;
    }
}
