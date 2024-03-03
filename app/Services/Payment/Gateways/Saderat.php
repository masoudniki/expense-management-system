<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Interfaces\PaymentGatewayInterface;

class Saderat implements PaymentGatewayInterface
{
    public function __construct(
        private array $config
    ){}
    public function processPayment(int $amount, string $iban)
    {
        // payment via saderat
    }
}
