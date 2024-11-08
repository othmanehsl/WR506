<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Slugify;

class DemoController extends AbstractController
{
    #[Route('/demo', name: 'demo')]
    public function index(): Response
    {
        $currentDateTime = new \DateTime();

        $slug = new Slugify();
        $demoSlug = $slug->slugify('Test phrase de Slug');

        return $this->render('demo/index.html.twig', [
            'currentDateTime' => $currentDateTime,
            'demoSlug' => $demoSlug,
        ]);
    }
}
