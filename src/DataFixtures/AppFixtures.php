<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addSeries($manager);
    }

    public function addSeries(ObjectManager $manager):void {


        $generator = Factory::create('fr_FR');


        for ($i=0;$i<50;$i++){

            $serie = new Serie();
            $serie
                ->setBackdrop("backdrop.png")
                ->setDateCreated($generator->dateTimeBetween('-20 years','now'))
                ->setGenres($generator->randomElement(["Comedie", "S-F", "Fantastique"]))
                ->setName($generator->word.$i)
                ->setFirstAirDate($generator->dateTimeBetween('-10 years','now'))
                ->setLastAirDate($generator->dateTimeBetween('-5 years','now'))
                ->setPopularity($generator->numberBetween(0,1000))
                ->setPoster("poster.png")
                ->setStatus("Canceled")
                ->setTmdbId(12345)
                ->setVote($generator->numberBetween(0,5));

                $manager->persist($serie);

        }
                $manager->flush();

    }
}
