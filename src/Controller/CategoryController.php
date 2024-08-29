<?php

namespace App\Controller;

use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    private $entityManager;
    private $categoryRepo;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepo)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepo = $categoryRepo;
    }

    #[Route('/categories', name: 'app_category')]
    public function index(): Response
    {
        $categories = $this->categoryRepo->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/create', name: 'app_category_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(CategoryFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/update/{id}', name: 'app_category_update')]
    public function updateCategory(int $id, Request $request): Response
    {
        $category = $this->categoryRepo->find($id);

        if ($category === null) {
            throw $this->createNotFoundException('CategoryAdmin not found');
        }

        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/delete/{id}', name: 'app_category_delete')]
    public function deleteUser(int $id): Response
    {
        $category = $this->categoryRepo->find($id);

        if ($category === null) {
            dd('error 404');
        }

        return $this->render('category/delete.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'app_category_delete_by_id', methods: ['POST'])]
    public function deleteCategoryById(int $id): Response
    {
        $category = $this->categoryRepo->find($id);

        if ($category) {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_category');
    }
}
