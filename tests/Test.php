<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8080'
        ]);
    }

    public function testPhones()
    {
        $expected = [
            '245.432.2028 x524',
            '1-259-640-9813 x969',
            '417-776-8892',
            '+1 (238) 263-4527',
            '1-571-454-4353 x322',
            '550-891-4483 x41047',
            '573-869-8135 x2893',
            '(409) 298-6892 x356',
            '+1 (930) 595-3970',
            '1-446-424-4025 x89768'
        ];
        $response = $this->client->get('/phones');
        $body = $response->getBody()->getContents();
        $actual = json_decode($body);
        $this->assertEquals($expected, $actual);
    }

    public function testDomains()
    {
        $expected = [
            'schroeder.com',
            'mueller.com',
            'luettgen.biz',
            'emard.info',
            'satterfield.com',
            'reynolds.com',
            'reichel.info',
            'douglas.net',
            'williamson.net',
            'carter.com'
        ];
        $response = $this->client->get('/domains');
        $body = $response->getBody()->getContents();
        $actual = json_decode($body);
        $this->assertEquals($expected, $actual);
    }
}
