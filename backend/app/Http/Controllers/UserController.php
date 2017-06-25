<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Article;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $users; // Returns all users in JSON. 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return 'create not working yet';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     //// RETURNS 500 SERVER ERROR
    public function store(Request $request)
    {
        // $user = new User();
		// $existingUser = User::where('email', $request['emai']);
		// if(!$existingUser->isEmpty()){
		// 	return 'exists';
		// }
		// else {
		// 	$user->name = $request['name'];
		// 	$user->email = $request['email'];
        //     $user->password = $request['password'];
		// 	if($user->save()){
		// 		return $user->id;
		// 	}
		// }
		// return 'error';
        return 'store not working properly';
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $user;
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
    public function update(Request $request, $id)
    {
        //
        return 'update not implemented';
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

    public function ArticleUsers(Article $article)
    {
        $users = $article -> users;
        return $users;
    }
}
