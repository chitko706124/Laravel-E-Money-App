<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->data['title'],
            'message' => $this->data['message'],
            'date' => $this->created_at,
            'deep_link' => $this->data['deep_link']
        ];
    }
}
