<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\CommentCollection as CommentResource;

use App\Models\Comment;
use App\Models\User;

class PostCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'likes' => $this->likes,
            'content' => $this->content,
            'user' => User::where('id', '=', $this->user_id)->first(),
            'comments' => CommentResource::collection(Comment::where('post_id', '=', $this->id)->get()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        
        return $response;
    }
}
