<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    public function register(Request $request, UserPasswordHasherInterface $passwordhash): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if ($user->getRoles() == ['ROLE_USER']) {
                $user->setRoleid(1);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $user->setPassword(
                    $passwordhash->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            } elseif ($user->getRoles() == ['ROLE_ADMIN']) {
                $user->setRoleid(2);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $user->setPassword(
                    $passwordhash->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            } else {

                $user->setRoleid(3);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $user->setPassword(
                    $passwordhash->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'utilisateur bien enregistrer');
            return $this->redirectToRoute('register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
