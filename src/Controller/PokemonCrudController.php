<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Form\PokemonType;
use App\Repository\TypeRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PokemonCrudController extends AbstractController {
    public function __construct(private PokemonRepository $pokemonRepository) {
    }

    #[Route('/pokemons', name: 'app_pokemon_list')]
    public function list(): Response {
        $pokemons = $this->pokemonRepository->findAll();
        return $this->render('pokemon_crud/list.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    #[Route('/pokemons/create', name: 'app_pokemon_create')]
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
            return $this->redirectToRoute('app_pokemon_list');
        }

        return $this->render('pokemon_crud/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/pokemons/{pokemon}', name: 'app_pokemon_details')]
    public function details(Pokemon $pokemon): Response {
        if (!$pokemon) {
            throw new NotFoundHttpException('Le Pokémon n\'existe pas.');
        }

        return $this->render('pokemon_crud/details.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }

    #[Route('/pokemons/{pokemon}/update', name: 'app_pokemon_update')]
    public function update(Pokemon $pokemon, EntityManagerInterface $em, Request $request): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($pokemon);
            $em->flush();
            $this->addFlash('success', 'Le Pokémon a été créé avec succès !');
            return $this->redirectToRoute('app_pokemon_list');
        }

        return $this->render('pokemon_crud/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/pokemons/{pokemon}/delete', name: 'app_pokemon_delete')]
    public function delete(Pokemon $pokemon, EntityManagerInterface $em): Response {
        $em->remove($pokemon);
        $em->flush();

        $this->addFlash('success', 'Le Pokémon a été supprimé avec succès !');

        return $this->redirectToRoute('app_pokemon_list');
    }
}
