<?php

namespace App\Controller;

use App\Repository\SousRubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SousRubriqueController extends AbstractController
{
    #[Route('/sous-rubrique', name: 'app_sous_rubrique')]
    //on injecte le repository dont on a besoin, on lui donne une variable.
    public function index(SousRubriqueRepository $sousRubriqueRepository): Response
    {
        return $this->render('sous_rubrique/index.html.twig', [
            //on nomme la variable, et on lui assigne toutes les sous rubriques de la bdd.
            'sous_rubriques' => $sousRubriqueRepository->findAll(),
        ]);
    }
}
