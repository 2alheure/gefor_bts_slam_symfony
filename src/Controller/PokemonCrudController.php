<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

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
    public function create(EntityManagerInterface $em, TypeRepository $typeRepository): Response {
        $salameche = new Pokemon;
        $salameche->setNom('Salameche');
        $salameche->setNum(4);
        $salameche->setDescription('Salamèche est un Pokémon de type Feu. Il est le Pokémon de départ de type Feu dans la région de Kanto.');
        $salameche->setZone('Kanto');
        $salameche->setImg('https://www.pokepedia.fr/images/thumb/5/5c/004Salam%C3%A8che.png/250px-004Salam%C3%A8che.png');
        $salameche->setType1(
            $typeRepository->findOneByNom('Feu')
        );
        // $salameche->setType2($this->getDoctrine()->getRepository(Type::class)->findOneBy(['nom' => 'Vol']));

        $em->persist($salameche);
        $em->flush();
        $this->addFlash('success', 'Le Pokémon a été créé avec succès !');
        return $this->redirectToRoute('app_pokemon_list');
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

    #[Route('/pokemons/{id}/update', name: 'app_pokemon_update')]
    public function update(EntityManagerInterface $em, TypeRepository $tr): Response {
        $bulbisaur = $this->pokemonRepository->findOneByNum(1);
        if (!$bulbisaur) {
            throw new NotFoundHttpException('Le Pokémon n\'existe pas.');
        }

        $bulbisaur->setType1($tr->findOneByNom('Plante'));
        
        $em->persist($bulbisaur);
        $em->flush();

        return $this->redirectToRoute('app_pokemon_list');
    }

    #[Route('/pokemons/{pokemon}/delete', name: 'app_pokemon_delete')]
    public function delete(Pokemon $pokemon, EntityManagerInterface $em): Response {
        $em->remove($pokemon);
        $em->flush();

        $this->addFlash('success', 'Le Pokémon a été supprimé avec succès !');
        
        return $this->redirectToRoute('app_pokemon_list');
    }
}
