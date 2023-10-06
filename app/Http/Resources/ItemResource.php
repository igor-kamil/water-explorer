<?php

namespace App\Http\Resources;

use App\Models\Authority;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'dating' => $this->dating,
            'description' => $this->description,
            'image_src' => $this->getImageRoute(), //$this->image_src,
            'web_url' => $this->web_url,
            'collection' => $this->collection,
            'object' => $this->object,
            'year_from' => $this->year_from,
            'year_to' => $this->year_to
        ];
    }

    private function getImageRoute()
    {
        return 'https://mdo.mkg-hamburg.de/MDO/mediadelivery/rendition/' . $this->asset_id . '/-FJPG-B1354'; // B1354 -> bounding box 1354px 
    }

}
