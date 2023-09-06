<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\User;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository, Request $request): Response
    {
        $search = $request->query->get("search");
        if($search === ""){
            return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->createQueryBuilder('p')
            ->where('p.name LIKE :search')
            ->setParameter('search', "%$search%")
            ->getQuery()
            ->getResult()
        ]);
    }

    #[Route('/fav', name: 'app_produit_favoris', methods: ['GET'])]
    public function showFavorite(): Response
    {
        if($this->getUser()){
            return $this->render('favoris/index.html.twig');
        }
        return $this->redirectToRoute('app_login');
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProduitRepository $produitRepository, SluggerInterface $slugger): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

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


            $produitRepository->save($produit, true);

            //$this->addFlash(
            //    'success',
            //    'Produit ajouté avec succès !!'
            //);

            return $this->redirectToRoute('app_produit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository, SluggerInterface $slugger, Filesystem $filesystem): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {

            $imgProduit = $form['picture']->getData();

            if ($imgProduit != null){
                $filesystem->remove(
                    $this->getParameter('img_product_directory').'/'.$produit->getPicture());
                $originalFilename = pathinfo($imgProduit->getClientOriginalName(), PATHINFO_FILENAME);
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

            $produitRepository->save($produit, true);

            $this->addFlash(
                'success',
                'Produit modifié avec succès !!'
            );

            return $this->redirectToRoute('app_produit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $filesystem->remove(
                $this->getParameter('img_product_directory').'/'.$produit->getPicture());
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_produit', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}/addfav', name: 'app_produit_add_favoris', methods: ['POST'])]
    public function addFavorite( EntityManagerInterface $em, Produit $produit, Request $request): Response
    {
        $referer = $request->headers->get('referer');
        $user = $this->getUser();
        if(!$produit->getFavoris()->contains($user)){
            $produit->addFavori($user);
        }else{
            $produit->removeFavori($user);
        }
        $em->persist($produit);
        $em->flush();

        return $this->redirect($referer, Response::HTTP_SEE_OTHER);
    }

    
}
