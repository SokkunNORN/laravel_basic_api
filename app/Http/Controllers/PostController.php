<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PostCollection as PostResource;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response  = PostResource::collection(Post::paginate());
    
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
        
        if (empty($request->title)) {
            return 'Field "title" is required.';
        }

        if (Post::where('title', '=', $request->title)->first()) {
            return 'The title already exists.';
        }

        if (strlen($request->title) < 10) {
            return 'The title should be more then 10 characters long.';
        }

        if (strlen($request->title) > 70) {
            return 'The title should be less or qual then 70 characters long.';
        }

        if (empty($request->slug)) {
            return 'Field "slug" is required.';
        }

        if (DB::table('posts')->where('slug', $request->slug)->first()) {
            return 'The slug already exists.';
        }

        if (strlen($request->slug) < 10) {
            return 'The slug should be more then 10 characters long.';
        }

        if (strlen($request->slug) > 80) {
            return 'The slug should be less or qual then 80 characters long.';
        }

        $post = Post::create([
            "title" => $request->title,
            "slug" => $request->slug,
            "content" => $request->content,
            "user_id" => Auth::id()
        ]);

        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = [
            "status" => [
                "errorCode" => 0,
                "message" => "Success"
            ],
            "data" => Post::find($id)
        ];
        return $response;
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
        
        $post = Post::find($id);
    
        if (empty($post)) {
            return 'Post id "' . $id . '" is undefined!';
        }
        
        $request->validate([
            'slug' => 'required|max:125|min:10|unique:posts'
        ]);

        if ($id === Auth::id()) {
            $post->update([
                "title" => $request->title,
                "slug" => $request->slug,
                "content" => $request->content
            ]);
            
            return $post;
        } else {
            return 'Post id ' . $id . ' is not belong to the user logging';
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
        $post = Post::find($id);

        if (empty($post)) {
            return 'Post id "' . $id . '" is undefined!';
        }

        Post::destroy($id);

        return $post;
    }
    
}
