<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use DateTimeImmutable;
use DateTime;
use App\Entity\Actor;
use App\Entity\Movie;
use App\Entity\Category;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    { 
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Person($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
    
        $actors = $faker->actors(null, 190, false);
        $createdActors = [];
        
        foreach ($actors as $item) {
            $fullname = $item;
            $fullnameExploded = explode(' ', $fullname);
    
            $firstname = $fullnameExploded[0]; 
            $lastname = $fullnameExploded[1]; 
    
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
    
        $categories = []; 
    
        $genres = $faker->movieGenres(21);
        foreach ($genres as $genreTitle) {
            $category = new Category();
            $category->setTitle($genreTitle)
                     ->setCreatedAt(new DateTimeImmutable());
            $manager->persist($category);
            $categories[] = $category;
        }
    
        $movies = $faker->movies(100);
        foreach ($movies as $item) {
            $movie = new Movie();
            $movie->setTitle($item)
                  ->setDescription($faker->overview(200))
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
