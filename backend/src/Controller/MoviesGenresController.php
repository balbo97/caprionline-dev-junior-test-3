<?php

namespace App\Controller;

use App\Repository\MovieGenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MoviesGenresController extends AbstractController
{
    public function __construct(
        private MovieGenreRepository $movieGenreRepository
    ) {
    }

    #[Route('/movie_genres', methods: ['GET'])]
    public function index(): Response
    {
        $movieGenres = $this->movieGenreRepository->findAll();

        // Costruiamo manualmente un array di dati
        $data = [];
        foreach ($movieGenres as $movieGenre) {
            $data[] = [
                'id' => $movieGenre->getId(),
                'movie_id' => $movieGenre->getMovie()->getId(),
                'genre_id' => $movieGenre->getGenre()->getId(),

            ];
        }

        // Convertiamo l'array in formato JSON
        $jsonData = json_encode($data);

        // Restituiamo una risposta JSON
        return new JsonResponse($jsonData, Response::HTTP_OK, [], true);
    }
}
