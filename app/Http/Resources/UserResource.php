<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'githubToken' => $this->githubToken,
            'token_verify' => $this->token_verify,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
