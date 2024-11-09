<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function listProducts(): Response
    {
        return $this->render('product/list.html.twig', [
            'title' => 'Liste des produits',
        ]);
    }
    
    #[Route('/product/{id}', name: 'product_view')]
    public function viewProduct($id): Response
    {
        return $this->render('product/view.html.twig', [
            'id' => $id,
        ]);
    }
}
