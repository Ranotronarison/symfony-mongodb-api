<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class ArticlesApiTest extends ApiTestCase
{
    public function setUp(): void
    {
        $application = new Application(self::bootKernel());
        $application->setAutoExit(false);
        $input = new ArrayInput([
          "command" => "doctrine:mongodb:fixtures:load",
          "--no-interaction" => true,
          "--env" => "test"
        ]);

        $application->run($input);
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

    public function testGetArticleCollection()
    {
        $token = $this->getToken();
        static::createClient()->request(
            'GET',
            '/api/articles',
            [
                "headers" => ["Authorization" => "Bearer ".$token]
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testCreateArticle()
    {
        $token = $this->getToken();
        static::createClient()->request(
            'POST',
            '/api/articles',
            [
                "headers" => ["Authorization" => "Bearer ".$token],
                "json" => [
                    "name" => "New Article",
                    "description" => "Article's description",
                    "price" => 25.5,
                    "quantity" => 5
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(201);
    }
}
