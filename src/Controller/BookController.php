<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    public function __construct(private readonly BookRepository $bookRepository)
    {
    }

    #[Route('/book', name: 'app_book')]
    public function index(): JsonResponse
    {
        return $this->json([
            'data' => $this->bookRepository->findAll()
        ]);
    }
}
