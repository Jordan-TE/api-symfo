<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager) :void
    {
        for ($i = 0; $i < 20; $i++) {
            $post = new Book();
            $post->setAuthor('Author name '.$i);
            $post->setTitle('Lorem ipsum '.$i);
            $post->setPages(mt_rand(10, 350));
            $post->setSummary('lorem ipsum summary '.$i);
            $manager->persist($post);
        }
        $manager->flush();
    }
}
