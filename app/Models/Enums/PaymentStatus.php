<?php

namespace App\Models\Enums;

enum PaymentStatus
{
    const SUCCESS = 'success';
    const FAILED = 'failed';
    const PENDING = 'pending';
}
