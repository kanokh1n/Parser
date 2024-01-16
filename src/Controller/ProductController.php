<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    #[Route('/products', name: 'product_list')]
    public function products(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/products.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/{id}', name: 'product_detail')]
    public function product($id, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($id);

        return $this->render('product/product.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/sellers', name: 'seller_list')]
    public function sellers(EntityManagerInterface $entityManager): Response
    {
        $sellers = $entityManager->getRepository(Seller::class)->findAll();

        return $this->render('seller/sellers.html.twig', [
            'sellers' => $sellers,
        ]);
    }

    #[Route('/sellers/{id}', name: 'seller_detail')]
    public function seller($id, EntityManagerInterface $entityManager)
    {
        $seller = $entityManager->getRepository(Seller::class)->find($id);
        $productCount = count($seller->getProducts());

        return $this->render('seller/seller.html.twig', [
            'seller' => $seller,
            'productCount' => $productCount,
        ]);
    }


}