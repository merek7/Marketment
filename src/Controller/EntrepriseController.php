<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EntrepriseController extends AbstractController
{
    private $entreprise;
    private $security;
    public function __construct(EntrepriseRepository $entreprise, Security $security)
    {
        $this->entreprise = $entreprise;
        $this->security = $security;
    }

    public function new(Request $request)
    {
        $today = new DateTime('now');
        $entreprise = new Entreprise;
        $user = $this->security->getUser();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entreprise->setDateajout($today);
            $entreprise->setUsers($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entreprise);
            $entityManager->flush();
            # code...
        }
        return $this->render(
            'entreprise/index.html.twig',
            [
                'EntrepriseForm' => $form->createView(),
            ]
        );
    }
    public function list(): Response
    {

        return $this->render(
            'entreprise/list.html.twig',
            [
                'EntrepriseList' => $this->entreprise->findAll()
            ]
        );
    }
}
