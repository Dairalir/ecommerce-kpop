<?php

namespace App\Service;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private RequestStack $requestStack;

    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function getTotal()
    {
        $cart = $this->getSession()->get("cart", []);
        $dataCart = [];
        foreach($cart as $id => $quantite) {
            $produit = $this->em->getRepository(Produit::class)->findOneBy(['id' => $id]);
            $dataCart[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
        }
        return $dataCart;
    }
    
    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}
