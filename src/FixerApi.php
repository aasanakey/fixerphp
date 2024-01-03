<?php

namespace Aasanakey\Fixer;

use DateTime;
use GuzzleHttp\Client;

class FixerApi
{
    const  DATE_FORMAT = 'Y-m-d';

    private string $baseUrl = "https://api.apilayer.com/fixer";

    private array $endpoints = [
        'convert' => 'convert',
        'fluctuation' => 'fluctuation',
        'latest' => 'latest',
        'symbols' => 'symbols',
        'timeseries' => 'timeseries',
    ];

    public function __construct(private Client $client,private ?string $apiKey)
    {
        $this->client = $client;
    }

    protected function getUrl(?string $type)
    {
        $baseUrl = trim($this->baseUrl, '/');
        if (array_key_exists($type, $this->endpoints)) {
            $endpoint = trim($this->endpoints[$type], '/');
            return  "{$baseUrl}/{$endpoint}";
        }

        return $baseUrl;
    }

    protected function formatDate(\DateTime $date)
    {
        return $date->format(self::DATE_FORMAT);
    }

    /**
     * Convert any amount from one currency to another.
     *
     * @param  float $amount
     * @param  string $from
     * @param  string $to
     * @param  string $date
     * @return mixed|null
     * @throws \Exception
     */
    public function convert(float $amount, string $from, string $to, ?string $date = null)
    {
        $url = $this->getUrl('convert');
        $date = $date ? $this->formatDate(new DateTime($date)) : $date;
        $response = $this->client->get(
            $url,
            [
                'headers' => [
                    'apikey' => $this->apiKey
                ],
                'query' => compact('amount', 'to', 'from', 'date')
            ]
        );

        return $response;
    }
    
    /**
     * Retrieve information about how currencies fluctuate on a day-to-day basis. 
     * To use this feature, simply append a start_date and end_date and choose which 
     * currencies (symbols) you would like to query the API for. Please note that the maximum 
     * allowed timeframe is 365 days.
     *
     * @param  string $start_date
     * @param  string $end_date
     * @param  array $symbols
     * @param  string $base
     * @return mixed|null
     * @throws \Exception
     */
    public function fluctuation(string $start_date, string $end_date, ?array $symbols = null, ?string $base = null)
    {
        $start_date = $start_date ? $this->formatDate(new DateTime($start_date)) : $start_date;
        $end_date = $end_date ? $this->formatDate(new DateTime($end_date)) : $end_date;
        $symbols = isset($symbols) ? implode(',', $symbols) : $symbols;
        $url = $this->getUrl('fluctuation');
        $response = $this->client->get(
            $url,
            [
                'headers' => [
                    'apikey' => $this->apiKey
                ],
                'query' => compact('start_date', 'end_date','symbols','base')
            ]
        );
        return $response;
    }
    
    /**
     * Get daily historical rates between two dates, with a maximum time frame of 365 days.
     *
     * @param  string $start_date
     * @param  string $end_date
     * @param  array $symbols
     * @param  mixed $base
     * @return mixed
     * @throws \Exception
     */
    public function timeseries(string $start_date, string $end_date, ?array $symbols = null, ?string $base = null)
    {
        $start_date = $start_date ? $this->formatDate(new DateTime($start_date)) : $start_date;
        $end_date = $end_date ? $this->formatDate(new DateTime($end_date)) : $end_date;

        $url = $this->getUrl('timeseries');
        $symbols = isset($symbols) ? implode(',', $symbols) : $symbols;
        $response = $this->client->get(
            $url,
            [
                'headers' => [
                    'apikey' => $this->apiKey
                ],
                'query' => compact('end_date', 'start_date', 'symbols', 'base')
            ]
        );
        return $response;
    }
    
    /**
     * Get real-time exchange rate data updated every 60 minutes, every 10 minutes or every 60 seconds.
     *
     * @param  array $symbols
     * @param  string $base
     * @return mixed
     * @throws \Exception
     */
    public function latest(array $symbols, string $base)
    {
        $url = $this->getUrl('latest');
        $symbols = isset($symbols) ? implode(',', $symbols) : $symbols;
        $response = $this->client->get(
            $url,
            [
                'headers' => [
                    'apikey' => $this->apiKey
                ],
                'query' => compact('symbols', 'base')
            ]
        );
        return $response;
    }
    
    /**
     * Get Historical rates for most currencies all the way back to the year of 1999.
     *
     * @param  string $date
     * @param  array $symbols
     * @param  string $base
     * @return mixed
     * @throws \Exception
     */
    public function historic(string $date,?array $symbols = null,?string $base = null)
    {
        $date = $date ? $this->formatDate(new DateTime($date)) : $date;
        $symbols = isset($symbols) ? implode(',',$symbols) : $symbols;
        
        $url = $this->getUrl('lates') . "/{$date}";
        $response = $this->client->get(
            $url,
            [
                'headers' => [
                    'apikey' => $this->apiKey
                ],
                'query' => compact('symbols', 'base')
            ]
        );
        return $response;
    }
}
