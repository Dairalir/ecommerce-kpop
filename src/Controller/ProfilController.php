<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Repository\UserRepository;
use App\Repository\ClientRepository;
use App\Repository\AddressRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/profil')]
class ProfilController extends AbstractController
{
    #[Route('/', name: 'app_profil')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        $address = $this->getUser()->getAddresses();
        $commande = $this->getUser()->getCommandes();

        return $this->render('profil/index.html.twig', [
            'commandes' => $commande,
            'addresses' => $address,

        ]);
    }
    #[Route('/addressNew', name: 'app_adress_new')]
    public function addressNew(Request $request, AddressRepository $addressRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $address = new Address;
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setUser($this->getUser());
            $addressRepository->save($address, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('profil/new_address.html.twig', [
            'form' => $form->createView(),

        ]);
    }

    #[Route('{id}/addressEdit', name: 'app_address_edit')]
    public function addressEDit(Address $address, Request $request, AddressRepository $addressRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setUser($this->getUser());
            $addressRepository->save($address, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('profil/edit_address.html.twig', [
            'address' => $address,
            'form' => $form->createView(),

        ]);
    }

    #[Route('/{id}', name: 'app_address_delete', methods: ['POST'])]
    public function delete(Request $request, Address $address, AddressRepository $addressRepository, Filesystem $filesystem): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->request->get('_token'))) {
            $addressRepository->remove($address, true);
        }

        return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/userEdit', name: 'app_user_edit')]
    public function userEdit(User $user, Request $request, UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->save($user, true);

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('profil/edit_user.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
