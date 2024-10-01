<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AuthenticationTest extends ApiTestCase
{
    public function testAuthSuccess()
    {
        $response = static::createClient()->request(
            'POST',
            '/auth',
            [
        'json' => ['email' => 'test@test.com', 'password' => 'testpass']
        ]
        );
        $this->assertResponseStatusCodeSame(200);

        $data = $response->toArray();
        $this->assertArrayHasKey('token', $data);
    }

    public function testUnauthenticatedRequest()
    {
        static::createClient()->request(
            'GET',
            '/api/users'
        );

        $this->assertResponseStatusCodeSame(403);
        $this->assertJsonContains([
            'status'  => '403 Forbidden',
            'message' => 'AUTHENTICATION_FAILED',
        ]);
    }
}
