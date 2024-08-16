<?php

namespace App\Controller;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepo
    ) {
    }

    #[Route('/users', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->userRepo->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/users/ajouter', name: 'app_user_ajouter')]
    public function ojouterUser(): Response
    {
        $users = $this->userRepo->findAll();

        return $this->render('user/ajouter.html.twig', [
            'users' => $users,
        ]);
    }



    #[Route('/users/read/{id}', name: 'app_user_read')]
    public function readUser(int $id): Response
    {
        $user = $this->userRepo->find($id);

        if ($user === null)
        {
            dd('error 404');
        }

        return $this->render('user/read.html.twig', [
            'user' => $user,
        ]);


    }

    #[Route('/users/update/{id}', name: 'app_user_update')]
    public function updateUser(int $id): Response
    {
        $user = $this->userRepo->find($id);

        if ($user === null)
        {
            dd('error 404');
        }

        return $this->render('user/update.html.twig', [
            'user' => $user,
        ]);


    }

    #[Route('/users/{id}/delete', name: 'app_user_delete')]
    public function deleteUser(int $id): Response
    {
        $user = $this->userRepo->find($id);

        if ($user === null)
        {
            dd('error 404');
        }

        return $this->render('user/read.html.twig', [
            'user' => $user,
        ]);


    }

}
