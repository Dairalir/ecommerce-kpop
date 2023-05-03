<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show')]
    // on utilise la classe de l'entité et on lui assigne une variable pour afficher le contenu de la classe
    public function show(Produit $produit):Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}
