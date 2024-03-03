<?php

namespace App\Services\Payment\Interfaces;

interface PaymentGatewayInterface
{
    public function processPayment(int $amount,string $iban);
}
