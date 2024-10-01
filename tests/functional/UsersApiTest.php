<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class UsersApiTest extends ApiTestCase
{
    public function setUp(): void
    {
        $application = new Application(self::bootKernel());
        $application->setAutoExit(false);
        $input = new ArrayInput([
          "command" => "doctrine:mongodb:fixtures:load",
          "--no-interaction" => true,
          "--env" => "test",
          "--quiet" => true
        ]);

        $application->run($input);
    }

    public function createUser()
    {
        $createUserResponse = static::createClient()->request(
            'POST',
            '/api/users',
            [
                "json" => [
                    "email" => "test02@test.com",
                    "plainPassword" => "testpass"
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
                'json' => ["email" => "test@test.com", "password" => "testpass"]
            ]
        );

        return $response->toArray()["token"];
    }

    public function testGetUserCollection()
    {
        static::createClient()->request(
            'GET',
            '/api/articles',
            [
                "headers" => ["Authorization" => "Bearer ".$this->getToken()]
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
                "json" => [
                    "email" => "test02@test.com",
                    "plainPassword" => "testpass"
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
                "json" => [
                    "email" => "test02@test.com",
                    "plainPassword" => "testpass"
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        $data = $createUserResponse->toArray();

        $newUserId = $data["id"];

        static::createClient()->request(
            'PATCH',
            '/api/users/'. $newUserId,
            [
                "headers" => [
                    "content-type" => "application/merge-patch+json",
                    "Authorization" => "Bearer ".$this->getToken()
                ],
                "json" => [
                    "plainPassword" => "newpassword"
                ]
            ]
        );

        $this->assertResponseIsSuccessful();

        $loginResponse = static::createClient()->request(
            'POST',
            '/auth',
            [
                'json' => ["email" => "test02@test.com", "password" => "newpassword"]
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey("token", $loginResponse->toArray());
    }

    public function testHardDeleteUser()
    {
        $user = $this->createUser();
        $deleteUserResponse = static::createClient()->request(
            "DELETE",
            "/api/users/" . $user["id"],
            ["headers" => ["Authorization" => "Bearer ".$this->getToken()]]
        );
        $this->assertEquals(204, $deleteUserResponse->getStatusCode());
    }
}
