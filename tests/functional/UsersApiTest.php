<?php

namespace App\Functional\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\TestUtils;

class UsersApiTest extends ApiTestCase
{
    private TestUtils $testUtils;

    public function setUp(): void
    {
        $this->testUtils = new TestUtils(self::bootKernel());
    }

    public function createUser($email, $password)
    {
        $createUserResponse = static::createClient()->request(
            'POST',
            '/api/users',
            [
                'json' => [
                    'email' => 'test02@test.com',
                    'plainPassword' => 'testpass'
                ]
            ]
        );

        return $createUserResponse->toArray();
    }

    protected function getToken()
    {
        $response = static::createClient()->request(
            'POST',
            '/auth',
            [
                'json' => ['email' => 'test@test.com', 'password' => 'testpass']
            ]
        );

        return $response->toArray()['token'];
    }

    public function testGetUserCollection()
    {
        $this->testUtils->launchFixtures(['users']);
        static::createClient()->request(
            'GET',
            '/api/articles',
            [
                'headers' => ['Authorization' => 'Bearer ' . $this->getToken()]
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser()
    {
        static::createClient()->request(
            'POST',
            '/api/users',
            [
                'json' => [
                    'email' => 'testnewuser@test.com',
                    'plainPassword' => 'testpass'
                ]
            ]
        );
        $this->assertResponseStatusCodeSame(201);
    }

    public function testUpdatePassword()
    {
        $createUserResponse = static::createClient()->request(
            'POST',
            '/api/users',
            [
                'json' => [
                    'email' => 'test04@test.com',
                    'plainPassword' => 'testpass'
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $data = $createUserResponse->toArray();

        $newUserId = $data['id'];

        static::createClient()->request(
            'PATCH',
            '/api/users/' . $newUserId,
            [
                'headers' => [
                    'content-type' => 'application/merge-patch+json',
                    'Authorization' => 'Bearer ' . $this->getToken()
                ],
                'json' => [
                    'plainPassword' => 'newpassword'
                ]
            ]
        );

        $this->assertResponseIsSuccessful();

        $loginResponse = static::createClient()->request(
            'POST',
            '/auth',
            [
                'json' => ['email' => 'test04@test.com', 'password' => 'newpassword']
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $loginResponse->toArray());
    }

    public function testHardDeleteUser()
    {
        $user = $this->createUser('test@mail.com', 'testpass');
        $deleteUserResponse = static::createClient()->request(
            'DELETE',
            '/api/users/' . $user['id'],
            ['headers' => ['Authorization' => 'Bearer ' . $this->getToken()]]
        );
        $this->assertEquals(204, $deleteUserResponse->getStatusCode());
    }
}
