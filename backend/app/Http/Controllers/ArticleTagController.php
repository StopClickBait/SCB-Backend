<?php

namespace App\Http\Controllers;

use App\ArticleTags;
use Illuminate\Http\Request;



class ArticleTagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($article)
    {
        //
		$articleTags = ArticleTags::where('articleID', $article)->get();
		return json_encode($articleTags) ;
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
		$articleTag = new ArticleTags;
		
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
		$articleTag = ArticleTags::find($id)->first();
		return $articleTag;
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
		
		return 'under construction';
		
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
		
		$articleTag = ArticleTags::find($id);
		
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
		$articleTag = ArticleTags::find($id);
		try{
			$articleTag->delete();
			return 'Tag Deleted.';
		} catch (Exception $e){
			return 'We failed you: '.$e;
		}
		
    }
}
