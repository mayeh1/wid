<?php

namespace App\Payments;

use RuntimeException;

class GatewayNotConfiguredException extends RuntimeException
{
    public static function forDriver(string $driver): self
    {
        return new self("The \"{$driver}\" payment gateway is not yet configured with API credentials. An administrator needs to add them in Payment Methods before this method can accept live donations.");
    }
}
