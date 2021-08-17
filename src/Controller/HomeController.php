<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use App\Repository\EntrepriseRepository;
use App\Repository\ProspectRepository;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private $users;
    private $security;
    private $entreprise;
    private $prospect;
    private $urlGenerator;
    private $rdv;
    public function __construct(UrlGeneratorInterface $urlGenerator, UserRepository $users, Security $security, EntrepriseRepository $entreprise, ProspectRepository $prospect, RdvRepository $rdv)
    {
        $this->users = $users;
        $this->security = $security;
        $this->entreprise = $entreprise;
        $this->prospect = $prospect;
        $this->urlGenerator = $urlGenerator;
        $this->rdv = $rdv;
    }

    public function index(): Response
    {
        return $this->render(
            'home/index.html.twig',
            [

                'CountUsers' => $this->users->CountUser(),
                'Users' => $this->users->findByRol(),
                'user' => $this->security->getUser()->getUserIdentifier(),
                'CountEntreprise' => implode('', $this->entreprise->CountEntreprise()),
                'CountProspect' => implode('', $this->prospect->CountProspect()),
                'CountRdv' => implode('', $this->rdv->Countrdv())

            ]
        );
    }
    public function userindex(): Response
    {
        if ($this->security->getUser() == null) {
            return new RedirectResponse($this->urlGenerator->generate('index'));
        }
        $identifier = $this->security->getUser()->getUserIdentifier();
        $identifierId = $this->users->UsernameToID($identifier);
        $countentreprise = $this->entreprise->CountMyEntreprise($identifier);
        $countprospect = $this->prospect->CountMyProspect($identifierId);

        return $this->render(
            'home/usercontent.html.twig',
            [

                'user' => $this->security->getUser()->getUserIdentifier(),
                'CountEntreprise' => implode($countentreprise),
                'CountProspect' => implode($countprospect)
            ]
        );
    }

    public function userslist(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // dd($this->users->ListUsersByRole());
        return $this->render(
            'user/list.html.twig',
            [
                'ListUsers' => $this->users->ListUsersByRole()

            ]
        );
    }

    public function edituser(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
