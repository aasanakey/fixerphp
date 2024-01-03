<?php

namespace Aasanakey\Fixer;

use GuzzleHttp\Client;

/**
 * @method static CurrencyConversion convert(?Client $client = null)
 * @method static CurrencyRates rates(?Client $client = null)
 */

class Fixer
{
    public static function __callStatic($name, $arguments)
    {
        $class = Currency::class;

        if (method_exists($class, $name)) {
            return (new $class())->$name(...$arguments);
        }

        throw new \Exception("Method {$name} not found");
    }
}
