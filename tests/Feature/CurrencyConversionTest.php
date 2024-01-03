<?php

use Aasanakey\Fixer\Fixer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

describe('currency conversion test', function () {

    test('can convert', function () {
        $apiResponse = '{
            "date": "2018-02-22",
            "historical": "",
            "info": {
              "rate": 148.972231,
              "timestamp": 1519328414
            },
            "query": {
              "amount": 25,
              "from": "GBP",
              "to": "JPY"
            },
            "result": 3724.305775,
            "success": true
          }';
        $this->client = Mockery::mock(Client::class);
        $this->client->shouldReceive('get')->andReturn(new Response(body: $apiResponse));
        $amount = 25;
        $from = 'GBP';
        $to = 'JPY';
        $result = Fixer::key('api key')
            ->convert($this->client)
            ->amount($amount)
            ->from($from)
            ->to($to)
            ->get();
        expect($result)->toBeFloat();
    });
});
