<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->setMethod('POST')
            ->setAction($this->generateUrl('app_user_create'))
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Name'],
                'required' => true,
            ])
            ->add('firstname', TextType::class, [
                'label' => 'First name',
                'attr' => ['class' => 'form-control', 'placeholder' => 'First Name'],
                'required' => false,
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Age',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Age'],
                'required' => false,
            ])
            ->add('tel', TelType::class, [
                'label' => 'Telephone',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Telephone'],
                'required' => false,
            ])
            ->add('pays', TextType::class, [
                'label' => 'Pays',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Country'],
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email Address',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Email Address'],
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Comment',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Comment'],
                'required' => false,
            ])
            ->add('metier', TextType::class, [
                'label' => 'Metier',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Job'],
                'required' => false,
            ])
            ->add('url', UrlType::class, [
                'label' => 'Website',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Website'],
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
                'attr' => ['class' => 'btn btn-success'],
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Reset',
                'attr' => ['class' => 'btn btn-secondary'],
            ])
            ->getForm();

        $form->handleRequest($request);

        return $this->render('user/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users/create', name: 'app_user_create', methods: 'POST')]
    public function createUser(Request $request): Response
    {
        $data = $request->get('form');

        $user = new User();
        $user->setName($data['name']);
        $user->setFirstname($data['firstname']);
        $user->setAge((int) $data['age']);
        $user->setTel($data['tel']);
        $user->setPays($data['pays']);
        $user->setEmail($data['email']);
        $user->setComment($data['comment']);
        $user->setMetier($data['metier']);
        $user->setUrl($data['url']);

        $this->userRepo->save($user);

        return $this->redirectToRoute('app_user');
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

    #[Route('/users/delete/{id}', name: 'app_user_delete')]
    public function deleteUser(int $id): Response
    {
        $user = $this->userRepo->find($id);

        if ($user === null)
        {
            dd('error 404');
        }

        return $this->render('user/delete.html.twig', [
            'user' => $user,
        ]);


    }

    #[Route('/users/{id}/delete', name: 'app_user_delete_by_id', methods: ['POST'])]
    public function deleteUserById(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->userRepo->find($id);

        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user');


    }

}
