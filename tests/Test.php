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

    public function testCompanies1()
    {
        $expected = [
            [ 'name' => 'Adams-Reichel','phone' => '1-986-987-9109 x56053' ],
            [ 'name' => 'Dibbert-Morissette','phone' => '439.584.3132 x735' ],
            [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
            [ 'name' => 'Kiehn-Mann','phone' => '972-379-1995 x61054' ],
            [ 'name' => 'Bosco, Pouros and Larson','phone' => '887-919-2730 x49977' ]
        ];
        $response = $this->client->get('/companies');
        $body = $response->getBody()->getContents();
        $companies = json_decode($body);
        $filteredCompanies = collect($companies)->map(function ($company) {
            return ['name' => $company->name, 'phone' => $company->phone];
        })->all();
        $this->assertEquals($expected, $filteredCompanies);
    }

    public function testCompanies2()
    {
        $expected = [
            [ 'name' => 'Ledner and Sons','phone' => '979-539-4173 x048' ],
        ];
        $response = $this->client->get('/companies?page=3&per=1');
        $body = $response->getBody()->getContents();
        $companies = json_decode($body);
        $filteredCompanies = collect($companies)->map(function ($company) {
            return ['name' => $company->name, 'phone' => $company->phone];
        })->all();
        $this->assertEquals($expected, $filteredCompanies);
    }

    public function testCompanies3()
    {
        $expected = [
            [ 'name' => 'Zemlak-Wuckert', 'phone' => '291-495-8263 x678' ],
            [ 'name' => 'Osinski, Kutch and Christiansen', 'phone' => '(843) 796-4156 x65355' ],
        ];
        $response = $this->client->get('/companies?page=20&per=2');
        $body = $response->getBody()->getContents();
        $companies = json_decode($body);
        $filteredCompanies = collect($companies)->map(function ($company) {
            return ['name' => $company->name, 'phone' => $company->phone];
        })->all();
        $this->assertEquals($expected, $filteredCompanies);
    }
}
