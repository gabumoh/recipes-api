<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecipeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'yeilds' => $this->yeilds,
            'prep_time' => $this->prep_time,
            'total_time' => $this->total_time,
            'average_rating' => $this->ratings->avg('rating'),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'ingredients' => $this->ingredients,
            'directions' => $this->directions,
            'ratings' => $this->ratings,
            'user' => $this->user,
        ];
    }
}
