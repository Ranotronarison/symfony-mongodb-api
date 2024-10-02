<?php

namespace App\DataFixtures;

use App\Document\User;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Bundle\MongoDBBundle\Fixture\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['users'];
    }

    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('test@test.com');
        $password = $this->hasher->hashPassword($user, 'testpass');
        $user->setPassword($password);

        $manager->getRepository(User::class)->save($user, true);

        $manager->flush();
    }
}
