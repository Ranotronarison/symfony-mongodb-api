<?php

declare(strict_types=1);

namespace App\Functional\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Document\Article;
use App\Tests\TestUtils;

class ArticlesApiTest extends ApiTestCase
{
    private TestUtils $testUtils;

    protected function setUp(): void
    {
        $this->testUtils = new TestUtils(self::bootKernel());
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

    public function createArticle($name, $description, $price, $quantity): Article
    {
        $container = self::getContainer();
        $documentManager = $container->get('doctrine_mongodb.odm.document_manager');

        $article = new Article();
        $article->setName($name);
        $article->setDescription($description);
        $article->setPrice($price);
        $article->setQuantity($quantity);
        $documentManager->persist($article);
        $documentManager->flush();

        return $article;
    }

    public function testGetArticleCollection()
    {
        $this->testUtils->launchFixtures(['articles', 'users']);
        $token = $this->getToken();
        static::createClient()->request(
            'GET',
            '/api/articles',
            [
                'headers' => ['Authorization' => 'Bearer ' . $token]
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
                'headers' => ['Authorization' => 'Bearer ' . $token],
                'json' => [
                    'name' => 'New Article',
                    'description' => "Article's description",
                    'price' => 25.5,
                    'quantity' => 5
                ]
            ]
        );

        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetArticleItem()
    {
        $article = $this->createArticle('Article', "Article's description", 25.5, 5);
        $token = $this->getToken();
        static::createClient()->request(
            'GET',
            '/api/articles/' . $article->getId(),
            [
                'headers' => ['Authorization' => 'Bearer ' . $token]
            ]
        );
        $this->assertResponseIsSuccessful();
    }

    public function testHardDeleteArticle()
    {
        $article = $this->createArticle('Article', "Article's description", 25.5, 5);
        $token = $this->getToken();
        static::createClient()->request(
            'DELETE',
            '/api/articles/' . $article->getId(),
            [
                'headers' => ['Authorization' => 'Bearer ' . $token]
            ]
        );
        $this->assertResponseStatusCodeSame(204);
    }

    public function testUpdateArticle()
    {
        $article = $this->createArticle('Article', "Article's description", 25.5, 5);
        $token = $this->getToken();
        static::createClient()->request(
            'PATCH',
            '/api/articles/' . $article->getId(),
            [
                'headers' => [
                    'content-type' => 'application/merge-patch+json',
                    'Authorization' => 'Bearer ' . $token
                ],
                'json' => [
                    'name' => 'Updated Article'
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
    }
}
