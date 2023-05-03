<?php

namespace App\Controller;

use App\Entity\SousRubrique;
use App\Repository\SousRubriqueRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sous-rubrique')]
class SousRubriqueController extends AbstractController
{
    #[Route('/', name: 'app_sous_rubrique')]
    //on injecte le repository dont on a besoin, on lui donne une variable.
    public function index(SousRubriqueRepository $sousRubriqueRepository): Response
    {
        return $this->render('sous_rubrique/index.html.twig', [
            //on nomme la variable, et on lui assigne toutes les sous rubriques de la bdd.
            'sous_rubriques' => $sousRubriqueRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_sous_rubrique_show')]
    // on utilise la classe de l'entité et on lui assigne une variable pour afficher le contenu de la classe
    public function show(SousRubrique $rubrique):Response
    {
        return $this->render('sous_rubrique/show.html.twig', [
            'sous_rubrique' => $rubrique,
        ]);
    }
}
