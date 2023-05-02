<?php

namespace App\DataFixtures;

use App\Entity\Fournisseur;
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
            $fournisseur = new Fournisseur();
            $fournisseur->setName("fournisseur_$i");
            $fournisseur->setAddress("adress_$i");
            $fournisseur->setCity("city_$i");
            $fournisseur->setPostalCode("postal_$i");
            $fournisseur->setCountry("country_$i");
            $manager->persist($fournisseur);
            $this->addReference("fournisseur_$i", $fournisseur);
        }

        for ($i = 0; $i < 5; $i++) {
            $rubrique = new Rubrique();
            $rubrique->setName('rubrique '.$i);
            $manager->persist($rubrique);
            $this->addReference("rubrique_$i", $rubrique);
        }
        // create sous rubrique
        for ($i = 0; $i < 5; $i++) {
            $sous_rubrique = new SousRubrique();
            $sous_rubrique->setName('sous_rubrique '.$i);
            $sous_rubrique->setPicture("photo_$i");
            $sous_rubrique->setRubrique($this->getReference("rubrique_".$i));
            $manager->persist($sous_rubrique);
            $this->addReference("sous_rubrique_$i", $sous_rubrique);
        }

            $produit = new Produit();
            $produit->setName('produit1 ');
            $produit->setDescription("description1");
            $produit->setPrice(10,99);
            $produit->setPicture("photo1");
            $produit->setStock(10);
            $produit->setActive(true);
            $produit->addSousRubrique($this->getReference("sous_rubrique_". 1 -1));
            $produit->setFournisseur($this->getReference("fournisseur_". 1 -1));
            $manager->persist($produit);

        $manager->flush();
    }
}
