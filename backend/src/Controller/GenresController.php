<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GenresController extends AbstractController
{
    public function __construct(
        private GenreRepository $genreRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/genres', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // Recupero tutti i generi dal repository
        $genres = $this->genreRepository->findAll();

        // Serializzo i dati in formato JSON
        $data = $this->serializer->serialize($genres, 'json');

        // Restituisco una risposta JSON con l'elenco dei generi
        return new JsonResponse($data, json: true);
    }
}
