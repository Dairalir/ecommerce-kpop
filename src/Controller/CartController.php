<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cart')]
class CartController extends AbstractController
{
    #[Route('/', name: 'app_cart')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $cart = $session->get("cart", []);

        //on "fabrique" les données
        $dataCart = [];
        $total = 0;

        foreach($cart as $id => $quantite) {
            $produit = $produitRepository->find($id);
            $dataCart[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
            $total += $produit->getPrice() * $quantite;
        }

        
        return $this->render('cart/index.html.twig', compact("dataCart", "total"));
    }

    #[Route('/add/{id}', name: 'app_cart_add')]
    public function add(Produit $produit, SessionInterface $session)
    {
        // recuperation panier actuelle
        $cart = $session->get("cart", []);
        $id = $produit->getId();

        if(!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        //on sauvegarde dans la session php
        $session->set("cart", $cart);

        return $this->redirectToRoute("app_cart");

    }

    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function remove(Produit $produit, SessionInterface $session)
    {
        // recuperation panier actuelle
        $cart = $session->get("cart", []);
        $id = $produit->getId();

        if(!empty($cart[$id])) {
            if($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }

        //on sauvegarde dans la session php
        $session->set("cart", $cart);

        return $this->redirectToRoute("app_cart");

    }

    #[Route('/delete/{id}', name: 'app_cart_delete')]
    public function delete(Produit $produit, SessionInterface $session)
    {
        // recuperation panier actuelle
        $cart = $session->get("cart", []);
        $id = $produit->getId();

        if(!empty($cart[$id])) {
            unset($cart[$id]);
        }

        //on sauvegarde dans la session php
        $session->set("cart", $cart);

        return $this->redirectToRoute("app_cart");

    }
}