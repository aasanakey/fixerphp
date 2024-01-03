<?php

namespace Aasanakey\Fixer;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class CurrencyRates
{
    /**
     * @var array|null
     */
    private $symbols = null;

    /**
     * @var int|null
     */
    private $places = null;

    /**
     * @var string|null
     */
    private $base = null;

    /**
     * @var string|null
     */
    private $historic_date = null;

    /**
     * @var string|null
     */
    private $start_date = null;

    /**
     * @var string|null
     */
    private $end_date = null;

    private $action = null;

    public function __construct(private ?Client $client = null, private $apiKey = '')
    {
        if(!$this->client) $this->client = new Client();
    }

    /**
     * @param string $currency
     *
     * @return $this
     */
    public function base(string $currency)
    {
        $this->base = $currency;
        return $this;
    }

    /**
     * @param array $symbols
     *
     * @return $this
     */
    public function symbols(array $symbols)
    {
        $this->symbols = $symbols;
        return $this;
    }

    /**
     * @param $places
     *
     * @return $this
     */
    public function round(int $places)
    {
        $this->places = $places;
        return $this;
    }

    /**
     * set start_date
     *
     * @param  string $date
     * @return $this
     */
    protected function start($date)
    {
        $this->start_date = $date;
        return $this;
    }

    /**
     * set end_date
     *
     * @param  string $date
     * @return $this
     */
    protected function end($date)
    {
        $this->end_date = $date;
        return $this;
    }

    /**
     * set historic_date
     *
     * @param  string $date
     * @return $this
     */
    protected function historic_date($date)
    {
        $this->historic_date = $date;
        return $this;
    }

    /**
     * @return $this
     */
    public function latest()
    {
        $this->action = 'latest';
        return $this;
    }

    /**
     * @param string $date
     * @return $this
     */
    public function historical(string $date)
    {
        $this->action = 'historic';
        return $this->historic_date($date);
    }

    /**
     * @param string      $date_from
     * @param string      $date_to
     *
     * @return $this
     */
    public function timeSeries(string $date_from, string $date_to)
    {
        $this->action = 'timeseries';
        return $this->start($date_from)->end($date_to);
    }

    /**
     * @param string      $date_from
     * @param string      $date_to
     *
     * @return $this
     */
    public function fluctuations(string $date_from, string $date_to)
    {
        $this->action = 'fluctuation';
        return $this->start($date_from)->end($date_to);
    }

    public function get()
    {
        $api = new FixerApi($this->client, $this->apiKey);
        $result = null;
        switch ($this->action) {
            case 'historic':
                $response = $api->historic($this->historic_date, $this->symbols, $this->base);
                $result = $this->getResults($response);
                break;

            case 'timeseries':
                $response = $api->timeseries($this->start_date, $this->end_date, $this->symbols, $this->base);
                $result = $this->getResults($response);
                break;

            case 'fluctuation':
                $response = $api->fluctuation($this->start_date, $this->end_date,$this->symbols,$this->base);
                $result = $this->getResults($response);
                break;

            case 'latest':
            default:
                $response = $api->latest($this->symbols, $this->base);
                $result = $this->getResults($response);
                break;
        }
        return $result;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return mixed|null
     */
    protected function getResults(ResponseInterface $response)
    {
        if ($response->getStatusCode() !== 200) return null;
        return $data = json_decode($response->getBody()->getContents(),true);
        //return array_key_exists('rates', $data) ? $data['rates'] : null;
    }
}
