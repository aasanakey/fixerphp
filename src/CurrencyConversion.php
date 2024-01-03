<?php
namespace Aasanakey\Fixer;

use Aasanakey\Fixer\FixerApi;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class CurrencyConversion extends FixerApi
{

    /**
     * Required base currency
     *
     * @var string|null
     */
    private $from = null;

    /**
     * Required target currency
     *
     * @var string|null
     */
    private $to = null;

    /**
     * @var int|null
     */
    private $places = null;

    /**
     * @var float
     */
    private $amount = 1.00;
    
    /**
     * @var \DateTime|null
     */
    private $date = null;

    public function __construct(?Client $client = null, $apiKey = '')
    {
        $client = $client ?? new Client();
        parent::__construct($client, $apiKey);
    }

    /**
     * @param $currency
     *
     * @return $this
     */
    public function from(string $currency)
    {
        $this->from = $currency;
        return $this;
    }

    /**
     * @param $currency
     *
     * @return $this
     */
    public function to(string $currency)
    {
        $this->to = $currency;
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
     * @param float $amount
     *
     * @return $this
     */
    public function amount(float $amount)
    {
        $this->amount = $amount;
        return $this;
    }
    
    /**
     *
     * @param  mixed $date
     * @return void
     */
    public function date(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }
    
    /**
     * get result from api
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function get()
    {
        $response = $this->convert($this->amount,$this->from,$this->to,$this->date);
        $result = $this->getResults($response);
        if($this->places && $result){
            return round($result,$this->places);
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
        if($response->getStatusCode() !== 200) return null;
        $data = json_decode($response->getBody()->getContents(),true);
        return array_key_exists('result',$data) ? $data['result'] : null;
    }

}
