<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Post;

class UserCollection extends JsonResource
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
            'name' => $this->title,
            'username' => $this->slug,
            'email' => $this->email,
            'posts' => Post::where('user_id', '=', $this->id)->get(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        
        return $response;
    }
}
