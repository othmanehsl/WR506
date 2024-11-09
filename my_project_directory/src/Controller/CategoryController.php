<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'app_category')]
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    ): Response {
        $queryBuilder = $entityManager->getRepository(Category::class)->createQueryBuilder('c')
            ->leftJoin('c.movies', 'm')
            ->addSelect('m')
            ->orderBy('c.title', 'ASC');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('category/index.html.twig', [
            'categories' => $pagination->getItems(),
            'currentPage' => $pagination->getPage(),
            'totalPages' => $pagination->getPageCount(),
        ]);
    }

    #[Route('/api/categories', name: 'api_categories_paginated', methods: ['GET'])]
    public function getCategoriesPaginated(
        Request $request,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $queryBuilder = $entityManager->getRepository(Category::class)->createQueryBuilder('c');

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        $categories = [];
        foreach ($pagination->getItems() as $category) {
            $categories[] = [
                'id' => $category->getId(),
                'title' => $category->getTitle(),
                'created_at' => $category->getCreatedAt()->format('Y-m-d H:i:s'),
                'movies' => $category->getMovies()->map(fn($movie) => $movie->getTitle())->toArray(),
            ];
        }

        return new JsonResponse([
            'categories' => $categories,
            'currentPage' => $pagination->getCurrentPageNumber(),
            'totalPages' => $pagination->getPageCount(),
        ]);
    }

    #[Route('/api/categories/{id}', name: 'api_category_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Catégorie introuvable.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'id' => $category->getId(),
            'title' => $category->getTitle(),
            'created_at' => $category->getCreatedAt()->format('Y-m-d H:i:s'),
            'movies' => $category->getMovies()->map(fn($movie) => $movie->getTitle())->toArray(),
        ]);
    }

    #[Route('/api/categories/{id}', name: 'api_category_delete', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Catégorie introuvable.'], Response::HTTP_NOT_FOUND);
        }

        // Vérifie si la catégorie est liée à des films
        if ($category->getMovies()->count() > 0) {
            return new JsonResponse([
                'error' => 'Impossible de supprimer la catégorie car elle est associée à un ou plusieurs films.'
            ], Response::HTTP_FORBIDDEN);
        }

        $entityManager->remove($category);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Catégorie supprimée avec succès.'], Response::HTTP_OK);
    }

    #[Route('/api/categories/{id}/movies', name: 'api_category_movies', methods: ['GET'])]
    public function getCategoryMovies(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $category = $entityManager->getRepository(Category::class)->find($id);

        if (!$category) {
            return new JsonResponse(['error' => 'Catégorie introuvable.'], Response::HTTP_NOT_FOUND);
        }

        $movies = $category->getMovies()->map(fn($movie) => [
            'id' => $movie->getId(),
            'title' => $movie->getTitle(),
        ])->toArray();

        return new JsonResponse($movies);
    }
}
