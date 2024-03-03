<?php

namespace App\Services\Payment;

use App\Services\Payment\Interfaces\PaymentGatewayInterface;
use InvalidArgumentException;

class PaymentProcessorService
{
    public static function getGateway(string $iban): ?string
    {
        $gateways = config('payments.gateways', []);
        $ibanPrefix = substr($iban, 0, 2);

        foreach ($gateways as $gateway => $data) {
            if ($ibanPrefix == $data['iban_prefix']) {
                return $gateway;
            }
        }

        return null;
    }
    public static function validateIban(string $iban): bool
    {
        $gateway = self::getGateway($iban);
        return $gateway !== false;
    }

    public static function create(string $iban):PaymentGatewayInterface{
        $gatewayName = self::getGateway($iban);

        // Check if a supported gateway is found
        if ($gatewayName !== null) {
            $gatewayClass = config("payments.gateways.$gatewayName.class");

            if (class_exists($gatewayClass)) {
                // set the configuration parameters
                return new $gatewayClass(config("payments.gateways.$gatewayName.class.config",[]));
            } else {
                throw new InvalidArgumentException("Gateway class $gatewayClass not found.");
            }
        } else {
            throw new InvalidArgumentException("No gateway found for the provided IBAN.");
        }
    }
}
