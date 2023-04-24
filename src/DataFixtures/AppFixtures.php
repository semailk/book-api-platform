<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100 ; $i++){
            $newBook = new Book();
            $newBook->setTitle('title_' . $i);
            $newBook->setIsbn(rand(5435, 353535));
            $newBook->setAuthor('Author ' . $i);
            $newBook->setDescription('Description ' . $i);

            $manager->persist($newBook);
            $manager->flush();
        }
    }
}
