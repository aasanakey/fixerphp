<?php

use Mockery;
use Aasanakey\Fixer\FixerApi;
use GuzzleHttp\Psr7\Response;

describe('fixer api test', function () {
    $apiKey = "VFAAfvCbLNn94pLb1jYAaZfKY7Unq9a4";
    test('can convert', function () use ($apiKey) {
        $this->client = Mockery::mock('GuzzleHttp\Client');
        $this->client->shouldReceive('get')->andReturn(new Response());
        $api = new FixerApi($this->client, $apiKey);
        $response = $api->convert(1, 'USD', 'GHS');
        $this->assertSame($response->getStatusCode(), 200);
    });

    test('can get timeseries', function () use ($apiKey) {
        $this->client = Mockery::mock('GuzzleHttp\Client');
        $this->client->shouldReceive('get')->andReturn(new Response());
        $start_date = '2023-05-15';
        $end_date = '2023-07-05';
        $api = new FixerApi($this->client, $apiKey);
        $response = $api->timeseries($start_date,$end_date);
        $this->assertSame($response->getStatusCode(), 200);
    });

    test('can get latest rates', function () use ($apiKey) {
        $this->client = Mockery::mock('GuzzleHttp\Client');
        $this->client->shouldReceive('get')->andReturn(new Response());
        $symbols = ["EUR","GBP","USD"];
        $base = "USD";
        $api = new FixerApi($this->client, $apiKey);
        $response = $api->latest($symbols,$base);
        $this->assertSame($response->getStatusCode(), 200);
    });

    test('can get historic rates', function () use ($apiKey) {
        $this->client = Mockery::mock('GuzzleHttp\Client');
        $this->client->shouldReceive('get')->andReturn(new Response());
        $symbols = ["EUR","GBP","USD"];
        $base = "USD";
        $date = '2023-05-15';
        $api = new FixerApi($this->client, $apiKey);
        $response = $api->historic($date,$symbols,$base);
        $this->assertSame($response->getStatusCode(), 200);
    });
});
