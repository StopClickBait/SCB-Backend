<?php

namespace App\Http\Controllers;
use App\Article;
use App\User;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return 'create not implemented';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = new Post();

        $post->user_id = $request['user_id'];
        $post->article_id = $request['article_id'];
        $post->text = $request['text'];
        $post->upvotes = 0;
        $post->downvotes = 0;
        if($post->save()){
            return $post;
        }
		return 'error';
    }

    /**
     * Display the specified resource.
     *
     * @param  Post $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return $post;
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
        return 'edit not implemented';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if($request['text']) {
            $post -> text = $request['text'];
        }
        if($post->save())
        {
            return $post;
        }
        return 'error';
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
        return 'destroy not implemented';
    }

    public function UserPosts(User $user)
    {
        // Not sure why the relationship isn't directly working:
        // return $user -> posts();
        $posts = Post::where('user_id', $user -> id)->get();
        return $posts;
    }

    public function ArticlePosts(Article $article)
    {
        // Not sure why the relationship isn't directly working:
        //return $article->posts();
        $posts = Post::where('article_id', $article -> id)->get();
        return $posts;
    }

    // returns the id, upvotes, and downvotes of the specified post.
    public function Votes(Post $post)
    {
        return collect($post)->only(['id', 'upvotes', 'downvotes'])->all();
    }

    public function Upvote(Post $post)
    {
        $post -> upvotes += 1;
        if($post->save())
        {     
            return $post->upvotes;
        }
    }

    public function Downvote(Post $post)
    {
        $post -> downvotes += 1;
        if($post -> save())
        {
            return $post -> downvotes;
        }
    }
}
