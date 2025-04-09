<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'register_id' => $this->register_id,
            'login' => $this->login,
            'password' => $this->password,
            'phone' => $this->phone,
        ];
    }
}
