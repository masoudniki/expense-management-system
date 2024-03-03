<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Interfaces\PaymentGatewayInterface;

class Meli implements PaymentGatewayInterface
{
    public function __construct(
        private array $config
    ){}

    public function processPayment(int $amount, string $iban)
    {
        // payment via Meli api
    }
}
