<?php

namespace App\Payments;

use App\Models\PaymentMethod;
use App\Payments\Drivers\AuthorizeNetDriver;
use App\Payments\Drivers\FlutterwaveDriver;
use App\Payments\Drivers\PaypalDriver;
use App\Payments\Drivers\PaystackDriver;
use App\Payments\Drivers\SquareDriver;
use App\Payments\Drivers\StripeDriver;
use InvalidArgumentException;

class PaymentGatewayManager
{
    private const DRIVER_MAP = [
        'stripe' => StripeDriver::class,
        'paypal' => PaypalDriver::class,
        'flutterwave' => FlutterwaveDriver::class,
        'paystack' => PaystackDriver::class,
        'square' => SquareDriver::class,
        'authorize_net' => AuthorizeNetDriver::class,
    ];

    public function driverFor(PaymentMethod $method): PaymentGatewayDriver
    {
        $class = self::DRIVER_MAP[$method->driver] ?? null;

        if (! $class) {
            throw new InvalidArgumentException("No gateway driver registered for \"{$method->driver}\".");
        }

        return app($class);
    }
}
