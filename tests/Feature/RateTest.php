<?php

use Mockery; 
use Aasanakey\Fixer\Fixer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

describe('historic rates test', function () {
    
    test('can get historic rates', function () {
        $apiResponse = '{
            "base": "EUR",
            "date": "2013-12-24",
            "historical": true,
            "rates": {
              "CAD": 1.739516,
              "EUR": 1.196476,
              "USD": 1.636492
            },
            "success": true,
            "timestamp": 1387929599
          }';
        $this->client = Mockery::mock(Client::class);
        $this->client->shouldReceive('get')->andReturn(new Response(body:$apiResponse));
        $date = '2013-12-24';
        $historic_rates = Fixer::key('api key')->rates($this->client)
            ->historical($date)
            ->get();
        $this->assertArrayHasKey('rates',$historic_rates);
        $this->assertTrue($historic_rates['historical']);
    });

    test('accept base currency', function () {
        $apiResponse = '{
            "base": "GBP",
            "date": "2013-12-24",
            "historical": true,
            "rates": {
              "CAD": 1.739516,
              "EUR": 1.196476,
              "USD": 1.636492
            },
            "success": true,
            "timestamp": 1387929599
          }';
        $this->client = Mockery::mock(Client::class);
        $this->client->shouldReceive('get')->andReturn(new Response(body:$apiResponse));
        $date = '2013-12-24';
        $base = 'GBP';
        $historic_rates = Fixer::key('api key')->rates($this->client)
            ->historical($date)
            ->base($base)
            ->get();
        $this->assertArrayHasKey('rates',$historic_rates);
        $this->assertTrue($historic_rates['historical']);
        $this->assertSame($base,$historic_rates['base']);
    });
});
