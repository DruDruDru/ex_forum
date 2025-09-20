<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'file_path' => $this->file_path,
            'user' => $this->user()->first(),
            'comments' => $this->comments()->with('user')->get(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'likes' => $this->likes()->count(),
            'dislikes' => $this->dislikes()->count(),
        ];
    }
}
