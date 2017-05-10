<?php

namespace App\Http\Controllers;

use App\ArticleTag;
use Illuminate\Http\Request;



class ArticleTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
	 * @param  int  $article
     * @return \Illuminate\Http\Response
     */
    public function index($article)
    {
        //
		$ArticleTags = ArticleTag::where('articleID', $article)->get();
		return json_encode($ArticleTags) ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
		
		return 'under construction'; 
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
		$articleTag = new ArticleTag;
		
		try{
			$articleTag->tagID = $request->tagID;
			$articleTag->userID = $request->userID;
			$articleTag->articleID = $request->articleID;
			
			$articleTag->save();
		
		return $articleTag->id;
		
		} catch (Exception $e){
			 return 'We failed you '.$e;
		}
		

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $article
	 * @param  int  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($article, $tag)
    {
        //
		$articleTag = ArticleTag::where([
				['tagID', '=', $tag],
				['articleID', '=', $article]
		])->get();
		return json_encode($articleTag);
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $article
	 * @param  int  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit($article, $tag)
    {
        //
		
		return 'Editing article number: '.$article.' -> tag number: '.$tag.'. ';
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
		
		$articleTag = ArticleTag::find($id);
		
		try{
			$articleTag->tagID = $request->tagID;
			$articleTag->userID = $request->userID;
			$articleTag->articleID = $request->articleID;
			
			$articleTag->save();
			return $articleTag->id;
		} catch(Exception $e){
			return 'We failed you: '.$e;
		}
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param	int $article
	 * @param 	int $tagID
     * @return 	\Illuminate\Http\Response
     */
    public function destroy($article, $tag)
    {
        //
		try{
			ArticleTag::where([
				['tagID', '=', $tag],
				['articleID', '=', $article]
			])->delete();
//			$articleTag->delete();
			return 'Tag Deleted.';
		} catch (Exception $e){
			return 'We failed you: '.$e;
		}
		
    }
}
