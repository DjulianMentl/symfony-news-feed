<?php

namespace App\DataFixtures;

use App\Entity\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserRoleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $userRole = new UserRole();
        $userRole->setUserId(1);
        $userRole->setRoleId(1);

        $manager->persist($userRole);
        $manager->flush();
    }
}