<?php

namespace App\Controller;

use App\Repository\MovieGenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MoviesGenresController extends AbstractController
{
    public function __construct(
        private MovieGenreRepository $movieGenreRepository
    ) {
    }

    #[Route('/movies_genres', methods: ['GET'])]
    public function index(Request $request): Response
    {
        // Ottieni il parametro di query per il genere selezionato, se presente
        $selectedGenre = $request->query->get('genre_id');

        // Se è stato specificato un genere, filtra i movieGenres per quel genere
        if ($selectedGenre) {
            $movieGenres = $this->movieGenreRepository->findBy(['genre' => $selectedGenre]);
        } else {
            // Altrimenti, ottieni tutti i movieGenres
            $movieGenres = $this->movieGenreRepository->findAll();
        }

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
