<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'description' => $this->resource->description,
            'amount' => $this->resource->amount,
            'iban' => $this->resource->iban,
            'national_code' => $this->resource->national_code,
            'reject_reason' => $this->resource->reject_reason,
            'is_confirmed' => $this->resource->is_confirmed,
            'user' => UserResource::make($this->resource->user),
            'attachments' => AttachmentResource::collection($this->resource->attachments)
        ];
    }
}
