<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'trx_no' => $this->rex_no,
            'ref_no' => $this->ref_no,
            'amount' => $this->amount,
            'type' => $this->type == 1 ? 'income' : 'expense',
            'date' => $this->created_at->format('Y-m-d H:i:s'),
            'source' => $this->source ? $this->source->name : '-',
            'description' => $this->description
        ];
    }
}
