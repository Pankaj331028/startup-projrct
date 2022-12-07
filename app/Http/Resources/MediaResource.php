<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'id' => $request->id,
            'original_url' => $request->original_url,
            'preview_url' => $request->preview_url,
            'thumb_url' => $request->getUrl('thumb'),
            'name' => $request->name,
            'file_name' => $request->file_name,
            'extension' => $request->extension,
            'size' => $request->size,
        ];
    }
}
