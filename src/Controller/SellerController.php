<?php

namespace App\Controller;

use App\Entity\Seller;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellerController extends AbstractController
{
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
