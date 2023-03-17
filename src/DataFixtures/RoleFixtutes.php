<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Persistence\ObjectManager;

class RoleFixtutes extends \Doctrine\Bundle\FixturesBundle\Fixture
{
    public function load(ObjectManager $manager)
    {
        $role = new Role();
        $role->setName('Administrator');
        $role->setCode('ADMIN');

        $manager->persist($role);
        $manager->flush();
    }
}