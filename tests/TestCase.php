<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $response;

    protected $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];

    private function getUser()
    {
        $user = User::first();

        return [
            'email' => $user['email'],
            'password' => 'Abc@123456',
        ];
    }

    public function setUp(): void
    {
        parent::setUp();

        $method = 'POST';
        $authEndpoint = '/api/auth';
        $data = $this->getUser();
        $response = $this->json($method, $authEndpoint, $data, $this->headers);
        $content = $response->getContent();
        $content = json_decode($content);
        $this->response = $content;
    }

    public function tearDown(): void
    {
        parent::tearDown();
    } 
}
