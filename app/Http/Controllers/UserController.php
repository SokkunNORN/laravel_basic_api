<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\UserCollection as UserResource;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::where('id', '!=', Auth::id())->get();
        $response  = UserResource::collection(User::all());
    
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (empty($request->name)) {
            return 'Field "name" is required.';
        }

        if (User::where('name', '=', $request->name)->first()) {
            return 'The name already exists.';
        }

        if (strlen($request->name) < 10) {
            return 'The name should be more then 10 characters long.';
        }

        if (strlen($request->name) > 70) {
            return 'The name should be less or qual then 70 characters long.';
        }

        $request->validate([
            'email' => 'string|email|max:255'
        ]);

        if (empty($request->email)) {
            return 'Field "email" is required.';
        }

        if (DB::table('users')->where('email', $request->email)->first()) {
            return 'The email already exists.';
        }

        if (empty($request->password)) {
            return 'Field "password" is required.';
        }

        if (strlen($request->password) < 8) {
            return 'The password should be more then 8 characters long.';
        }

        if (empty($request->username)) {
            return 'Field "username" is required.';
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
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
        $user = User::find($id);

        $request->only('name', 'email');

        if (empty($user)) {
            return 'User id "' . $id . '" is undefined!';
        }

        if (empty($request->name)) {
            return 'Field "name" is required.';
        }

        if (User::where('name', '=', $request->name)->first()) {
            return 'The name already exists.';
        }

        if (strlen($request->name) < 10) {
            return 'The name should be more then 10 characters long.';
        }

        if (strlen($request->name) > 70) {
            return 'The name should be less or qual then 70 characters long.';
        }

        $request->validate([
            'email' => 'string|email|max:255'
        ]);

        if (empty($request->email)) {
            return 'Field "email" is required.';
        }

        if (DB::table('users')->where('email', $request->email)->first()) {
            return 'The email already exists.';
        }

        $user->update($request->all());

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (empty($user)) {
            return 'User id "' . $id . '" is undefined!';
        }

        if (Auth::id() == $user['id']) {
            return "Cannot remove the user during logging....";
        }

        if ($user['id'] == 1) {
            return "Cannot remove default user....";
        }

        User::destroy($id);

        return $user;
    }
}
