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

    public function testUser()
    {
        $response = $this->client->get('/users/1');
        $body = $response->getBody()->getContents();
        $this->assertNotEmpty($body);
    }

    public function testUser2()
    {
        $response = $this->client->get('/users/99');
        $body = $response->getBody()->getContents();

        $this->assertContains('Grant', $body);
        $this->assertContains('Cara', $body);
        $this->assertContains('borer.nigel@gmail.com', $body);
    }

    public function testUser3()
    {
        $response = $this->client->get('/users/100');
        $body = $response->getBody()->getContents();

        $this->assertContains('Haley', $body);
        $this->assertContains('Stokes', $body);
    }

    public function testUsers()
    {
        $response = $this->client->get('/users');
        $body = $response->getBody()->getContents();

        $this->assertContains('Julianne', $body);
        $this->assertContains('Norval', $body);
    }
}
