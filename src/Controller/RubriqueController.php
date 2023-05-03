<?php

namespace App\Controller;

use App\Entity\Rubrique;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/rubrique')]
class RubriqueController extends AbstractController
{
    #[Route('/', name: 'app_rubrique')]
    public function index(): Response
    {
        return $this->render('rubrique/index.html.twig', [
            'controller_name' => 'RubriqueController',
        ]);
    }
    
    #[Route('/{id}', name: 'app_rubrique_show')]
    // on utilise la classe de l'entité et on lui assigne une variable pour afficher le contenu de la classe
    public function show(Rubrique $rubrique):Response
    {
        return $this->render('rubrique/show.html.twig', [
            'rubrique' => $rubrique,
        ]);
    }
}
