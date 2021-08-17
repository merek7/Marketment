<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use App\Repository\UserRepository;
use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class EntrepriseController extends AbstractController
{
    private $entreprise;
    private $security;
    public function __construct(UserRepository $user, EntrepriseRepository $entreprise, Security $security)
    {
        $this->entreprise = $entreprise;
        $this->security = $security;
        $this->user = $user;
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
            $this->addFlash('message', 'Entreprise ajouter  avec succes');
        }
        return $this->render(
            'entreprise/new.html.twig',
            [
                'EntrepriseForm' => $form->createView(),
            ]
        );
    }
    public function userlist(Request $request, PaginatorInterface $paginator)
    {
        $user = $this->user->UsernameToID($this->security->getUser()->getUserIdentifier());
        $donnees = $this->entreprise->findByidentifier(implode('', $user[0]));
        $list = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render(
            'entreprise/list.html.twig',
            [
                'EntrepriseList' => $list,
                'user' => true,
            ]
        );
    }
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        $donnees = $this->entreprise->findAll();

        $list = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            9
        );
        return $this->render(
            'entreprise/list.html.twig',
            [
                'EntrepriseList' => $list,
                'user' => false,
            ]
        );
    }

    public function print()
    {
        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->renderView('entreprise/print.html.twig', [
            'EntrepriseList' => $this->entreprise->findAll(),
            'Date' => new DateTime('now'),
            'user' => $this->security->getUser()->getUserIdentifier()
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fichier = 'list-' . $this->getUser()->getUserIdentifier() . '.pdf';
        $dompdf->stream(
            $fichier,
            [
                'Attachment' => false
            ]
        );
    }

    public function printbyuser()
    {
        $user = $this->user->UsernameToID($this->security->getUser()->getUserIdentifier());
        $donnees = $this->entreprise->findByidentifier(implode('', $user[0]));

        $pdfOptions = new Options();
        $pdfOptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => false
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->renderView('entreprise/print.html.twig', [
            'EntrepriseList' => $donnees,
            'Date' => new DateTime('now'),
            'user' => $this->security->getUser()->getUserIdentifier()
        ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $fichier = 'list-' . $this->getUser()->getUserIdentifier() . '.pdf';
        $dompdf->stream(
            $fichier,
            [
                'Attachment' => false
            ]
        );
    }
    public function edit(Entreprise $entreprise, Request $request): Response
    {

        $form = $this->createForm(EntrepriseType::class, $entreprise);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entreprise);
            $entityManager->flush();
        }
        return $this->render('entreprise/edit.html.twig', [
            'EntrepriseForm' => $form->createView()
        ]);
    }
}
