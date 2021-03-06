<?php

namespace Bambamboole\LaravelCms\Core\Posts\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'body' => $this->body,
            'author' => new \Bambamboole\LaravelCms\Core\Users\Resources\UserResource($this->whenLoaded('author')),
            'tags' => $this->whenLoaded('tags') instanceof MissingValue
                ? []
                : $this->whenLoaded('tags')->pluck('name'),
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
