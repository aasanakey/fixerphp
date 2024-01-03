<?php

namespace Aasanakey\Fixer;

use GuzzleHttp\Client;

class Currency
{

    public function __construct(private ?Client $client = null,private ?string $apiKey = null)
    {
        
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function key(string $apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @param Client|null $client
     *
     * @return CurrencyConversion
     * @throws \Exception
     */
    public function convert(?Client $client = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('Fixer Api Key is not provided!');
        }
    
        return new CurrencyConversion($client,$this->apiKey);
    }

    /**
     * @param Client|null $client
     * @return CurrencyRates
     * @throws \Exception
     */
    public function rates(?Client $client = null)
    {
        if (!$this->apiKey) {
            throw new \Exception('Fixer Api Key is not provided!');
        }
        return new CurrencyRates($client,$this->apiKey);
    }
}
