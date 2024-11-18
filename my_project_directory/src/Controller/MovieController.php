<?php

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movies', name: 'app_movie')]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    ): Response {
        $queryBuilder = $entityManager->getRepository(Movie::class)->createQueryBuilder('m')
            ->leftJoin('m.categories', 'c')
            ->addSelect('c')
            ->leftJoin('m.actors', 'a')
            ->addSelect('a')
            ->orderBy('m.title', 'ASC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $totalPages = ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage());

        return $this->render('movie/index.html.twig', [
            'movies' => $pagination->getItems(),
            'currentPage' => $pagination->getCurrentPageNumber(),
            'totalPages' => $totalPages,
        ]);
    }
}
