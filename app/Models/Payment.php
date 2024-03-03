<?php

namespace App\Models;

use App\Models\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
