<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\RecapDetails;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ProduitRepository;
use App\Service\CartService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Unique;

class OrderController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/order/create', name: 'app_order')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $cart = $session->get("cart", []);

        if (empty($cart)){
            return $this->redirectToRoute('app_cart');
        }

        //on "fabrique" les données
        $dataCart = [];
        $sousTotal = 0;
        $tva = 0;
        $livraison = 10;
        $total = 0;

        foreach($cart as $id => $quantite) {
            $produit = $produitRepository->find($id);
            $dataCart[] = [
                "produit" => $produit,
                "quantite" => $quantite
            ];
            $sousTotal += $produit->getPrice() * $quantite;
            $tva = $sousTotal / 100 * 10;
        }
        $total = $sousTotal + $tva + $livraison;

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'dataCart' => $dataCart,
            'sousTotal' => $sousTotal,
            'tva' => $tva,
            'livraison' => $livraison,
            'total' => $total
        ]);
        
    }

    #[Route('/order/verify', name: 'app_order_prepare', methods:['POST'])]
    public function prepareOrder(Request $request, CartService $cartService): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        //dd($form);

        if($form->isSubmitted() && $form->isValid()){
            $datetime = new DateTime('now');

            $transporter = $form->get('transporter')->getData();
            //$tansporterOrder = $transporter->getTitle();
            //$tansporterOrder .= '</br>'. $transporter->getContent();
            //$tansporterOrder .= '</br>'. $transporter->getPrice();

            $adressLiv = $form->get('addresses_liv')->getData();
            $adressLivOrder = $adressLiv->getFirstName(). ' ' . $adressLiv->getLastName();
            $adressLivOrder .= '</br>'. $adressLiv->getPhone();
            if($adressLiv->getCompany()){
                $adressLivOrder .= ' - '. $adressLiv->getCompany();
            }
            $adressLivOrder .= '</br>'. $adressLiv->getAddress();
            $adressLivOrder .= '</br>'. $adressLiv->getPostalcode() . ' - ' . $adressLiv->getCity();
            $adressLivOrder .= '</br>'. $adressLiv->getCountry();
            
            $adressFac = $form->get('addresses_fac')->getData();
            $adressFacOrder = $adressFac->getFirstName(). ' ' . $adressFac->getLastName();
            $adressFacOrder .= '</br>'. $adressFac->getPhone();
            if($adressFac->getCompany()){
                $adressFacOrder .= ' - '. $adressFac->getCompany();
            }
            $adressFacOrder .= '</br>'. $adressFac->getAddress();
            $adressFacOrder .= '</br>'. $adressFac->getPostalcode() . ' - ' . $adressFac->getCity();
            $adressFacOrder .= '</br>'. $adressFac->getCountry();


            $order = new Commande();
            $reference = $datetime->format('dmY').'-'.uniqid();
            $order->setUser($this->getUser());
            $order->setReference($reference);
            $order->setCreatedAt($datetime);
            $order->setDeliveryAddress($adressLivOrder);
            $order->setFacturationAddress($adressFacOrder);
            $order->setIsPaid(0);
            $order->setMethod('stripe');
            $order->setTransporterName($transporter->getTitle());
            $order->setTransporterPrice($transporter->getPrice());

            $this->em->persist($order);
            
            foreach ($cartService->getInfoCart() as $product)
            {
                $recapDetails = new RecapDetails();
                $recapDetails->setOrderProduct($order);
                $recapDetails->setQuantity($product['quantite']);
                $recapDetails->setPrice($product['produit']->getPrice());
                $recapDetails->setProduct($product['produit']->getName());
                $recapDetails->setTotalRecap($product['produit']->getPrice() * $product['quantite']);
                
                $this->em->persist($recapDetails);
            }
            $this->em->flush();

            return $this->render('order/recap.html.twig',[
                'method' => $order->getMethod(),
                'recapCart' => $cartService->getInfoCart(),
                'transporter' => $transporter,
                'addressLiv' => $adressLivOrder,
                'addressFac' => $adressFacOrder,
                'reference' => $order->getReference()
            ]);
        }
        
        return $this->redirectToRoute('app_cart');
    }
}
