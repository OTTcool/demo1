<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepo,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/products', name: 'app_product')]
    public function index(): Response
    {
        $products = $this->productRepo->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/create', name: 'app_product_create')]
    public function create(Request $request): Response
    {
        $form = $this->createForm(ProductFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_product');
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    #[Route('/products/create', name: 'app_product_create', methods: 'POST')]
//    public function createProduct(Request $request): Response
//    {
//        $data = $request->get('form');
//
////        $product = new Product();
////        $product->setName($data['name']);
////        $this->productRepo->save($product);
//
//        return $this->redirectToRoute('app_product');
//    }

    #[Route('/products/read/{id}', name: 'app_product_read')]
    public function readProduct(int $id): Response
    {
        $product = $this->productRepo->find($id);

        if ($product === null)
        {
            dd('error 404');
        }

        return $this->render('product/read.html.twig', [
            'product' => $product,
        ]);


    }

    #[Route('/products/update/{id}', name: 'app_product_update')]
    public function updateProduct(int $id, Request $request): Response
    {
        $product = $this->productRepo->find($id);

        if ($product === null) {
            throw $this->createNotFoundException('Product not found');
        }

        $form = $this->createForm(ProductFormType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $this->entityManager->flush();
            return $this->redirectToRoute('app_product');
        }
        return $this->render('product/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/products/delete/{id}', name: 'app_product_delete')]
    public function deleteProduct(int $id): Response
    {
        $product = $this->productRepo->find($id);

        if ($product === null)
        {
            dd('error 404');
        }

        return $this->render('product/delete.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}/delete', name: 'app_product_delete_by_id', methods: ['POST'])]
    public function deleteProductById(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $this->productRepo->find($id);

        if ($product) {
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product');


    }
}
