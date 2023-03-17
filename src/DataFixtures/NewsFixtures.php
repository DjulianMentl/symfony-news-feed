<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('ru_RU');

        for ($i = 0; $i < 30; $i++) {
            $img = 'img_' . $i . '.gif';

            $news = new News();
            $news->setTitle($faker->realText(50, 2));
            $news->setPreview($faker->realText(500, 2));
            $news->setText($faker->realText(2000, 2));
            $news->setDate($faker->dateTimeBetween('-30 days', '-1 days'));
            $news->setCounter($faker->numberBetween(5,100));
            $news->setImage($img);

            $manager->persist($news);
        }

        $manager->flush();
    }
}