<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use App\Entity\Rubrique;
use App\Entity\SousRubrique;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 5; $i++) {
            $rubrique = new Rubrique();
            $rubrique->setName('rubrique '.$i);
            $manager->persist($rubrique);
            $this->addReference("rubrique_$i", $rubrique);
        }
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('sous_rubrique '.$i);
            $sous_rubrique->setPicture("photo_$i");
            $sous_rubrique->setRubrique($this->getReference("rubrique_".mt_rand(0, 4)));
            $manager->persist($sous_rubrique);
        }

        $manager->flush();
    }
}
