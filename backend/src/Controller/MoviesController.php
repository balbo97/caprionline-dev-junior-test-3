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

        // Ottieni i film senza filtri
        $movies = $this->movieRepository->findAll();

        // Se è specificato un genere, ottieni i film di quel genere
        if ($genre) {
            $movies = $this->movieRepository->findMoviesByGenre($genre);
        }

        // Ordina i film se specificato un criterio di ordinamento
        if ($orderBy === 'recent') {
            usort($movies, function ($a, $b) {
                return $b->getYear() - $a->getYear(); // Ordina per anno decrescente
            });
        } elseif ($orderBy === 'rating') {
            usort($movies, function ($a, $b) {
                return $b->getRating() - $a->getRating(); // Ordina per rating decrescente
            });
        }



        $data = $this->serializer->serialize($movies, "json", ["groups" => "default"]);

        // Restituisco la risposta JSON
        return new JsonResponse($data, json: true);
    }
}
