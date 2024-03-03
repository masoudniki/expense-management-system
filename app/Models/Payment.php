<?php

namespace App\Models;

use App\Models\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory,HasUuids;
    protected $table = 'payments';
    protected $fillable = [
        'status'
    ];
    protected $casts = [
        'status' => PaymentStatus::class
    ];

    function uniqueIds()
    {
        return ['uuid'];
    }

    public function expense(): BelongsTo
    {
        return $this->belongsTo(Expense::class,'expense_request_id');
    }

}
