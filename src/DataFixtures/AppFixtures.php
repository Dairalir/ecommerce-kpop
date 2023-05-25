<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Rubrique;
use App\Entity\Fournisseur;
use App\Entity\SousRubrique;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 0; $i < 5; $i++) {
            $fournisseur = new Fournisseur();
            $fournisseur->setName("fournisseur_$i");
            $fournisseur->setAddress("adress_$i");
            $fournisseur->setCity("city_$i");
            $fournisseur->setPostalCode("postal_$i");
            $fournisseur->setCountry("country_$i");
            $manager->persist($fournisseur);
            $this->addReference("fournisseur_$i", $fournisseur);
        }

            $rubrique = new Rubrique();
            $rubrique->setName('Albums');
            $manager->persist($rubrique);
            $this->addReference("Albums", $rubrique);

            $rubrique = new Rubrique();
            $rubrique->setName('Merch');
            $manager->persist($rubrique);
            $this->addReference("Merch", $rubrique);

            $rubrique = new Rubrique();
            $rubrique->setName('Groupes');
            $manager->persist($rubrique);
            $this->addReference("Groupes", $rubrique);

            // create sous rubrique
            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('EP/mini Albums');
            $sous_rubrique->setPicture("");
            $sous_rubrique->setRubrique($this->getReference("Albums"));
            $manager->persist($sous_rubrique);
            $this->addReference("EP/mini Albums", $sous_rubrique);

            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('Albums Studio');
            $sous_rubrique->setPicture("");
            $sous_rubrique->setRubrique($this->getReference("Albums"));
            $manager->persist($sous_rubrique);
            $this->addReference("Albums Studio", $sous_rubrique);

            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('Lighstick');
            $sous_rubrique->setPicture("");
            $sous_rubrique->setRubrique($this->getReference("Merch"));
            $manager->persist($sous_rubrique);
            $this->addReference("Lighstick", $sous_rubrique);

            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('Dreamcatcher');
            $sous_rubrique->setPicture("dreamcatcherlogo.jpeg");
            $sous_rubrique->setRubrique($this->getReference("Groupes"));
            $manager->persist($sous_rubrique);
            $this->addReference("Dreamcatcher", $sous_rubrique);

            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('RedVelvet');
            $sous_rubrique->setPicture("redvelvetlogo.jpeg");
            $sous_rubrique->setRubrique($this->getReference("Groupes"));
            $manager->persist($sous_rubrique);
            $this->addReference("RedVelvet", $sous_rubrique);

            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('Twice');
            $sous_rubrique->setPicture("twicelogo.jpeg");
            $sous_rubrique->setRubrique($this->getReference("Groupes"));
            $manager->persist($sous_rubrique);
            $this->addReference("Twice", $sous_rubrique);

            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('(G)-idle');
            $sous_rubrique->setPicture("gidlelogo.jpeg");
            $sous_rubrique->setRubrique($this->getReference("Groupes"));
            $manager->persist($sous_rubrique);
            $this->addReference("(G)-idle", $sous_rubrique);

            $user = new User();
            $user->setEmail('daimanvm@gmail.com');
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setPassword('$2y$13$C3i2jiJXSpwfoXJs2OJ/jOhaJvPWfrfTbtqutlxG6W7JZb6GZUeiy'); //$2y$13$C3i2jiJXSpwfoXJs2OJ/jOhaJvPWfrfTbtqutlxG6W7JZb6GZUeiy   // 123456
            $user->setIsVerified(true);
            $manager->persist($user);


        $manager->flush();
    }
}
