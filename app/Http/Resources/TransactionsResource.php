<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = '';
        if ($this->type == 1) {
            $title = 'From ' . $this->source->name;
        } else {
            $title = 'To ' . $this->source->name;
        }
        return [
            'trx_no' => $this->trx_no,
            'amount' => $this->amount . ' MMK',
            'type' => $this->type == 1 ? 'income' : 'expense',
            'title' => $title,
            'date' => $this->created_at->format('Y-m-d H:i:s')
        ];
    }
}
