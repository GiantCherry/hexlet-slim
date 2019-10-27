<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8080',
            'http_errors' => false,
        ]);
    }

    public function testCourses()
    {
        $this->client->get('/courses');
        $response = $this->client->get('/courses/new');
        $body = $response->getBody()->getContents();
        $this->assertContains('course[title]', $body);
        $this->assertContains('course[paid]', $body);

        $formParams = ['course' => ['title' => '', 'paid' => '']];
        $response = $this->client->post('/courses', [
            /* 'debug' => true, */
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertContains("Can't be blank", $body);

        $formParams = ['course' => ['title' => 'course name', 'paid' => '']];
        $response = $this->client->post('/courses', [
            /* 'debug' => true, */
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertContains("Can't be blank", $body);
        $this->assertContains('course name', $body);

        $formParams = ['course' => ['title' => '', 'paid' => '1']];
        $response = $this->client->post('/courses', [
            /* 'debug' => true, */
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertContains("Can't be blank", $body);

        $formParams = ['course' => ['title' => '<script></script>', 'paid' => '']];
        $response = $this->client->post('/courses', [
            /* 'debug' => true, */
            'form_params' => $formParams
        ]);
        $body = $response->getBody()->getContents();
        $this->assertContains("&lt;script&gt;&lt;/script&gt;", $body);

        $formParams = ['course' => ['title' => '<script></script>', 'paid' => '1']];
        $response = $this->client->post('/courses', [
            'allow_redirects' => false,
            'form_params' => $formParams
        ]);
        $this->assertEquals($response->getStatusCode(), 302);
    }
}
