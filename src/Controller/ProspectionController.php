<?php

namespace App\Controller;

use App\Entity\Prospect;
use App\Entity\User;
use App\Form\ProspectionType;
use App\Repository\ProspectRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ProspectionController extends AbstractController
{

    private $prospect;
    private $security;
    private $user;

    public function __construct(ProspectRepository $prospect, Security $security, UserRepository $user)
    {
        $this->prospect = $prospect;
        $this->security = $security;
        $this->user = $user;
    }
    public function userlist(Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->user->UsernameToID($this->security->getUser()->getUserIdentifier());
        $donnees = $this->prospect->findByidentifier(implode('', $user[0]));
        $list = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
            'prospection/list.html.twig',
            [
                'prospectionList' => $list,
                'byuser' => 1
            ]
        );
    }
    public function list(Request $request, PaginatorInterface $paginator): Response
    {

        $donnees = $this->prospect->findAllbydate();
        $list = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render(
            'prospection/list.html.twig',
            [
                'prospectionList' => $list,
                'byuser' => 0
            ]
        );
    }

    public function new(Request $request)
    {
        $prospect = new Prospect;


        $form = $this->createForm(ProspectionType::class, $prospect);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prospect);
            $entityManager->flush();
            // $prospect->setEntreprises($this->entreprise->)
            $this->addFlash('message', 'Rapport ajouter avec succes');
            return $this->redirectToRoute('ProspectionListByUser');
        }
        return $this->render(
            'prospection/new.html.twig',
            [
                'ProspectionForm' => $form->createView()
            ]
        );
    }
    public function edit(Prospect $prospect, Request $request)
    {
        $form = $this->createForm(ProspectionType::class, $prospect);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($prospect);
            $entityManager->flush();
            $this->addFlash('message', 'Agents modifier avec succes');
            return $this->redirectToRoute('ProspectionListByUser');
        }
        return $this->render(
            'prospection/edit.html.twig',
            [
                'ProspectionForm' => $form->createView()
            ]
        );
    }
}
