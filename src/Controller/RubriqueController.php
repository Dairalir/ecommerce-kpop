<?php

namespace App\Controller;

use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RubriqueController extends AbstractController
{
    #[Route('/rubrique', name: 'app_rubrique')]
    public function index(RubriqueRepository $rubriqueRepository): Response
    {
        return $this->render('rubrique/index.html.twig', [
            'rubriques' => $rubriqueRepository->findAll(),
        ]);
    }
}
