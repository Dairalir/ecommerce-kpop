<?php

namespace App\Controller;

use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProduitRepository;

class OrderController extends AbstractController
{
    #[Route('/order/create', name: 'app_order')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'dataCart' => $dataCart,
            'total' => $total
        ]);
        
    }
}
