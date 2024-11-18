<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use DateTimeImmutable;
use App\Entity\Actor;
use App\Entity\Movie;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = FakerFactory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Person($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
    
        // Génération de 50 noms d'acteurs
        $actors = [];
        for ($i = 0; $i < 50; $i++) {
            $actors[] = $faker->unique()->name();  // Utiliser name() pour les noms d'acteurs
        }

        $createdActors = [];
        foreach ($actors as $fullname) {
            $fullnameExploded = explode(' ', $fullname);
            $firstname = $fullnameExploded[0];
            $lastname = $fullnameExploded[1] ?? '';

            $dob = $faker->dateTimeThisCentury();
            $actor = new Actor();
            $actor->setLastname($lastname)
                  ->setFirstname($firstname)
                  ->setDob($dob)
                  ->setAwards($faker->numberBetween(0, 10))
                  ->setBio($faker->text(200))
                  ->setNationality($faker->country())
                  ->setMedia($faker->imageUrl(640, 480, 'actors', true))
                  ->setGender($faker->randomElement(['male', 'female', 'other']))
                  ->setCreatedAt(new DateTimeImmutable());

            if ($faker->boolean(30)) {
                $deathDate = $faker->dateTimeBetween($dob, 'now');
                $actor->setDeathDate($deathDate);
            }
            
            $createdActors[] = $actor;
            $manager->persist($actor);
        }
    
        // Génération de 21 catégories (genres de films)
        $categories = [];
        for ($i = 0; $i < 21; $i++) {
            $category = new Category();
            $category->setTitle($faker->word())  // Utiliser word() pour les genres de films
                     ->setCreatedAt(new DateTimeImmutable());
            $manager->persist($category);
            $categories[] = $category;
        }
    
        // Génération de 100 films
        for ($i = 0; $i < 100; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->sentence(3)) 
                  ->setDescription($faker->text(200))
                  ->setReleaseDate($faker->dateTimeThisCentury())
                  ->setDuration($faker->numberBetween(1, 480))
                  ->setEntries($faker->numberBetween(0, 1000000))
                  ->setDirector($faker->name())
                  ->setRating($faker->randomFloat(1, 0, 10))
                  ->setMedia($faker->imageUrl(640, 480, 'movies', true))
                  ->setCreatedAt(new DateTimeImmutable());
    
            shuffle($createdActors);
            $createdActorsSliced = array_slice($createdActors, 0, 4);
            foreach ($createdActorsSliced as $actor) {
                $movie->addActor($actor);
            }
    
            shuffle($categories);
            $categoriesSliced = array_slice($categories, 0, mt_rand(1, 3));
            foreach ($categoriesSliced as $category) {
                $movie->addCategory($category);
            }
            
            $manager->persist($movie);
        }
    
        $manager->flush();
    }
}
