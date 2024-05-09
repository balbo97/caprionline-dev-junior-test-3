<?php

namespace App\Controller;

use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MoviesController extends AbstractController
{
    public function __construct(
        private MovieRepository $movieRepository,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/movies', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        // Recupero il criterio di ordinamento dalla query 
        $orderBy = $request->query->get('orderBy');

        // Recupero il genere dalla query
        $genre = $request->query->get('genre');

        if ($genre) {
            $movies = $this->movieRepository->findMoviesByGenre($genre);
        } else {
            // Altrimenti, recupero tutti i film
            $movies = $this->movieRepository->findAll();

            // Se è specificato un criterio di ordinamento, ordino i film di conseguenza
            if ($orderBy === 'recent') {
                $movies = $this->movieRepository->findBy([], ['year' => 'DESC']);
            } elseif ($orderBy === 'rating') {
                $movies = $this->movieRepository->findBy([], ['rating' => 'DESC']);
            }
        }



        $data = $this->serializer->serialize($movies, "json", ["groups" => "default"]);

        // Restituisco la risposta JSON
        return new JsonResponse($data, json: true);
    }
}
