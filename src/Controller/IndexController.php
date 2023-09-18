<?php

namespace App\Controller;

use App\Entity\RecapDetails;
use App\Repository\ProduitRepository;
use App\Repository\RecapDetailsRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProduitRepository $produitRepository, EntityManagerInterface $em): Response
    {

        return $this->render('index/index.html.twig', [
            'produits' => $produitRepository->findByTopSell(),
        ]);
    }
}
