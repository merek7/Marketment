<?php

namespace App\Controller;


use App\Entity\Rdv;
use App\Form\RdvType;
use App\Repository\ProspectRepository;
use App\Repository\RdvRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class RdvController extends AbstractController
{
    private $rdv;
    private $user;
    private $security;
    private $prospect;
    public function __construct(RdvRepository $rdv, UserRepository $user, Security $security, ProspectRepository $prospect)
    {
        $this->rdv = $rdv;
        $this->user = $user;
        $this->security = $security;
        $this->prospect = $prospect;
    }
    public function new(Request $request): Response
    {
        $rdv = new Rdv;
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rdv);
            $entityManager->flush();
            $this->addFlash('message', 'Rendez-vous ajouter avec succes');
        }

        return $this->render(
            'rdv/new.html.twig',
            [
                'RdvForm' => $form->createView()
            ]
        );
    }

    public function userlist(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->user->UsernameToID($this->security->getUser()->getUserIdentifier());
        $user = $this->prospect->ProspectByUserQuery($user);
        $donnees = $this->rdv->findAllbydate();
        $list = $paginator->paginate(

            $user,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'rdv/listbyuser.html.twig',
            [
                'Rdvlist' => $list
            ]
        );
    }

    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $this->rdv->findAllbydate();
        $list = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
            'rdv/list.html.twig',
            [
                'Rdvlist' => $list
            ]
        );
    }
    public function edit(Rdv $rdv, Request $request)
    {
        $form = $this->createForm(RdvType::class, $rdv);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rdv);
            $entityManager->flush();
            $this->addFlash('message', 'Rendez-vous modifier avec succes');
            return $this->redirectToRoute('RdvListByUser');
        }
        return $this->render(
            'rdv/edit.html.twig',
            [
                'RdvForm' => $form->createView()
            ]
        );
    }
}
