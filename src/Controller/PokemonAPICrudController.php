<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PokemonAPICrudController extends AbstractController {
    public function __construct(private PokemonRepository $pokemonRepository) {
    }

    #[Route('/api/pokemons', name: 'app_pokemon_api_list', methods: ['GET'])]
    public function list(): Response {
        $pokemons = $this->pokemonRepository->findAll();
        return new JsonResponse($pokemons);
    }

    #[Route('/api/pokemons', name: 'app_pokemon_api_create', methods: ['POST'])]
    public function create(EntityManagerInterface $em, Request $request): Response {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Vous n\'avez pas les droits nécessaires pour créer un Pokémon.');
        }

        $pokemon = new Pokemon;

        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pokemon);
            $em->flush();
            $this->addFlash('success', 'Le Pokémon a été créé avec succès !');
            return new JsonResponse($pokemon, Response::HTTP_CREATED);
        } else {
            return new JsonResponse([
                'message' => 'Le formulaire n\'est pas valide.',
                'errors' => $form->getErrors(true, false),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/pokemons/{pokemon}', name: 'app_pokemon_api_details', methods: ['GET'])]
    public function details(Pokemon $pokemon): Response {
        if (!$pokemon) {
            throw new NotFoundHttpException('Le Pokémon n\'existe pas.');
        }

        return new JsonResponse($pokemon);
    }

    #[Route('/api/pokemons/{pokemon}', name: 'app_pokemon_api_update', methods: ['PUT'])]
    public function update(Pokemon $pokemon, EntityManagerInterface $em, Request $request): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pokemon);
            $em->flush();
            return new JsonResponse($pokemon);
        } else {
            return new JsonResponse([
                'message' => 'Le formulaire n\'est pas valide.',
                'errors' => $form->getErrors(true, false),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/pokemons/{pokemon}', name: 'app_pokemon_api_delete', methods: ['DELETE'])]
    public function delete(Pokemon $pokemon, EntityManagerInterface $em, Request $request): Response {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Vous n\'avez pas les droits nécessaires pour supprimer un Pokémon.');
        }

        if (!$this->isCsrfTokenValid('delete-pokemon-' . $pokemon->getId(), $request->query->get('csrf'))) {
            throw new AccessDeniedHttpException('Le token CSRF est invalide.');
        }

        $em->remove($pokemon);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
