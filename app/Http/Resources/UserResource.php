<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'role_id' => $this->role_id,
            'name' => $this->name ? $this->name : $this->fullName(),
            'first_name' => $this->first_name ?? '',
            'last_name' => $this->last_name ?? '',
            'email' => $this->email ?? '',
            'user_phone' => $this->user_phone ?? '',
            'user_type' => $this->user_type ?? '',
            'user_name' => $this->user_name ?? '',
            'email_verified_at' => $this->email_verified_at ?? '',
            'is_verified' => $this->is_verified ?? '',
            'status' => $this->status ?? '',
            'remember_token' => $this->remember_token ?? '',
            'bearer_tokens' => $this->when(filled($this->access_token), $this->access_token),
            'profile_photos' => $this->userProfilePhotos(),
        ];
    }
}
