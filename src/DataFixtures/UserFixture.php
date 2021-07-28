<?php

namespace App\DataFixtures;

use App\Factory\ApiTokenFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        UserFactory::createMany(10, ['apiTokens' => ApiTokenFactory::new()->many(1, 3)]);
        UserFactory::new()->adminUsers()->create(['apiTokens' => ApiTokenFactory::new()->many(1, 3)]);
    }
}
