<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function index(): Response
    {

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
}
