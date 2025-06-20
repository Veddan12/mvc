<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller Product entities.
 */
final class ProductController extends AbstractController
{
     /**
     * Renders the product index page.
     */
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * Creates a new product.
     */
    #[Route('/product/create', name: 'product_create')]
    public function createProduct(
        EntityManagerInterface $entityManager
    ): Response {
        $product = new Product();
        $product->setName('Keyboard_num_' . rand(1, 9));
        $product->setValue(rand(100, 999));

        $entityManager->persist($product);
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    /**
     * Show all products as JSON response.
     */
    #[Route('/product/show', name: 'product_show_all')]
    public function showAllProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository
            ->findAll();

        $response = $this->json($products);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    /**
     * Show one product by ID as JSON.
     * Throws 404 if not found.
     */
    #[Route('/product/show/{id}', name: 'product_by_id')]
    public function showProductById(
        ProductRepository $productRepository,
        int $id
    ): Response {
        $product = $productRepository
            ->find($id);

        return $this->json($product);
    }

    /**
     * Delete a product by its ID.
     * Throws 404 if product not found.
     */
    #[Route('/product/delete/{id}', name: 'product_delete_by_id')]
    public function deleteProductById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return $this->redirectToRoute('product_show_all');
    }

     /**
     * Updates the value of a product by ID.
     *
     * @param int $id The ID of the product to update.
     * @param int $value The new value to assign.
     */
    #[Route('/product/update/{id}/{value}', name: 'product_update')]
    public function updateProduct(
        ManagerRegistry $doctrine,
        int $id,
        int $value
    ): Response {
        $entityManager = $doctrine->getManager();
        $product = $entityManager->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $product->setValue($value);
        $entityManager->flush();

        return $this->redirectToRoute('product_show_all');
    }

    /**
     * Renders a view listing all products.
     */
    #[Route('/product/view', name: 'product_view_all')]
    public function viewAllProduct(
        ProductRepository $productRepository
    ): Response {
        $products = $productRepository->findAll();

        $data = ['products' => $products];

        return $this->render('product/view.html.twig', $data);
    }

    
    /**
     * Renders products with value greater than or equal to a given minimum.
     *
     * @param int $value Minimum product value to filter by.
     */
    #[Route('/product/view/{value}', name: 'product_view_minimum_value')]
    public function viewProductWithMinimumValue(
        ProductRepository $productRepository,
        int $value
    ): Response {
        $products = $productRepository->findByMinimumValue($value);

        $data = ['products' => $products];

        return $this->render('product/view.html.twig', $data);
    }

    /**
     * Show products with value >= given minimum as JSON response.
     */
    #[Route('/product/show/min/{value}', name: 'product_by_min_value')]
    public function showProductByMinimumValue(
        ProductRepository $productRepository,
        int $value
    ): Response {
        $products = $productRepository->findByMinimumValue2($value);

        return $this->json($products);
    }
}
