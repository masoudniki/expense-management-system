<?php

namespace App\Services\Payment\Gateways;

use App\Services\Payment\Interfaces\PaymentGatewayInterface;

class Keshavarzi implements PaymentGatewayInterface
{
    public function __construct(
        private array $config
    ){}
    public function processPayment(int $amount, string $iban)
    {
        // payment via keshavarzi api
    }
}
