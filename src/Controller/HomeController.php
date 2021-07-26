<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $users;
    private $security;
    public function __construct(UserRepository $users, Security $security)
    {
        $this->users = $users;
        $this->security = $security;
    }

    public function index(): Response
    {
        // dd($this->security->getUser()->getUserIdentifier());
        return $this->render(
            'home/index.html.twig',
            [

                'CountUsers' => $this->users->CountUser(),
                'Users' => $this->users->findByRol()
            ]
        );
    }

    public function userslist(): Response
    {
        return $this->render(
            'home/Userslist.html.twig',
            [
                'ListUsers' => $this->users->ListUsersByRole()

            ]
        );
    }

    public function edituser(User $user, Request $request): Response
    {

        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($user->getRoles() == ['ROLE_USER']) {
                $user->setRoleid(1);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
            } elseif ($user->getRoles() == ['ROLE_ADMIN']) {
                $user->setRoleid(2);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
            } else {

                $user->setRoleid(3);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Agents modifier avec succes');
            return $this->redirectToRoute('Userslist');
        }
        return $this->render('home/edituser.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
