<?php
// src/KK/StartBundle/DataFixures/ORM/LoadPosts.php

namespace KK\StartBootStrapBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use KK\StartBootStrapBundle\Entity\Post;

/**
 * Class LoadPosts
 * @package KK\StartBootStrapBundle\DataFixtures\ORM
 */
class LoadPosts extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $post1 = new Post();
        $post1->setTitle('Test Post 1');
        $post1->setImage('slide-1.jpg');
        $post1->setCreatedAt(new \DateTime());
        $post1->setUpdatedAt($post1->getCreatedAt());

        $post2 = new Post();
        $post2->setTitle('Test Post 2');
        $post2->setImage('slide-2.jpg');
        $post2->setCreatedAt(new \DateTime());
        $post2->setUpdatedAt($post1->getCreatedAt());

        $post3 = new Post();
        $post3->setTitle('Test Post 3');
        $post3->setImage('slide-3.jpg');
        $post3->setCreatedAt(new \DateTime());
        $post3->setUpdatedAt($post1->getCreatedAt());

        $manager->persist($post1);
        $manager->persist($post2);
        $manager->persist($post3);
        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }

}