<?php

namespace App\Controller;

use App\Repository\MovieGenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MoviesGenresController extends AbstractController
{
    public function __construct(
        private MovieGenreRepository $movieGenreRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/movies_genres', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // Recupero tutte le associazioni film-genere da repository 
        $movies_genres = $this->movieGenreRepository->findAll();

        // Serializzo i dati in formato JSON
        $data = $this->serializer->serialize($movies_genres, 'json');

        // Restituisco una risposta JSON con l'elenco dei generi
        return new JsonResponse($data, json: true);
    }
}
