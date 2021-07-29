<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Factory\CommentFactory;
use App\Factory\QuestionFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        QuestionFactory::createMany(20);

        CommentFactory::createMany(
            120,
            function () {
                return ['question' => QuestionFactory::random()];
            }
        );

        QuestionFactory::new()->unpublished()->createMany(5);
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
        ];
    }
}
