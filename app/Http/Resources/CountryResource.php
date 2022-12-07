<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'country_name' => $this->country_name,
            'country_code' => $this->country_code ?? '',
            'default_language_short_code' => $this->default_language_short_code ?? '',
            'default_language' => new DefaultLanguageResource($this->defaultLanguage),
            // 'is_active' => $this->is_active,
            // 'status' => $this->status,
            // 'createdby_userid' => $this->createdby_userid,
            // 'updatedby_userid' => $this->updatedby_userid,
            // 'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            // 'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
