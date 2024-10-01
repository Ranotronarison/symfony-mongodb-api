<?php

namespace App\DataFixtures;

use App\Document\Article;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['articles'];
    }

    public function load(ObjectManager $manager)
    {
        $n = 5;
        for ($i = 0; $i < $n; $i++) {
            $article = new Article();
            $article->setName('Article ' . $i);
            $article->setDescription('Article ' . $i);
            $article->setPrice(rand(10, 50));
            $article->setQuantity(rand(1, 5));

            $manager->persist($article);
        }

        $manager->flush();
    }
}
