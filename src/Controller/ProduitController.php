<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, SluggerInterface $slugger): Response
    {
        $produit = new Produit();
        // création du formulaire
        $form = $this->createForm(ProduitType::class, $produit);
        // lecture du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produitRepository->save($produit, true);

            $imgProduit = $form['picture']->getData();

        if($imgProduit){
            $originalFilename = pathinfo($imgProduit->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imgProduit->guessExtension();

                try {
                    $imgProduit->move(
                        $this->getParameter('img_product_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $produit->setPicture($newFilename);
            }

            return $this->redirectToRoute('produit', [], Response::HTTP_SEE_OTHER);
            
        }

        return $this->render('produit/new.html.twig', [
                'produit' => $produit,
                'form' => $form->createView(),
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
