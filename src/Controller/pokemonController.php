<?php
declare(strict_types=1);

namespace App\Controller;
use App\Entity\Pokemon;
use App\Repository\PokemonRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;




class pokemonController extends AbstractController
{

    private array $pokemons;

    public function __construct()
    {

        $this->pokemons = [
            [
                'id' => 1,
                'title' => 'Carapuce',
                'content' => 'Pokemon eau',
                'isPublished' => true,
                'img' => 'carapuce.png'
            ],
            [
                'id' => 2,
                'title' => 'Salamèche',
                'content' => 'Pokemon feu',
                'isPublished' => true,
                'img' => "salameche.png"
            ],
            [
                'id' => 3,
                'title' => 'Bulbizarre',
                'content' => 'Pokemon plante',
                'isPublished' => true,
                'img' => "bulbizarre.png"
            ],
            [
                'id' => 4,
                'title' => 'Pikachu',
                'content' => 'Pokemon electrique',
                'isPublished' => true,
                'img' => "250px-Pikachu-DEPS.png"
            ],
            [
                'id' => 5,
                'title' => 'Dracolosse',
                'content' => 'Pokemon dragon',
                'isPublished' => true,
                'img' => 'dracolosse.png'
            ],
            [
                'id' => 6,
                'title' => 'Roucool',
                'content' => 'Pokemon vol',
                'isPublished' => true,
                'img' => "Sprite_0016_HOME.png"
            ],
            [
                'id' => 7,
                'title' => 'Kabutops',
                'content' => 'Pokemon roche',
                'isPublished' => false,
                'img' => "kabutops.png"
            ],
            [
                'id' => 8,
                'title' => 'Kangourex',
                'content' => 'Pokemon normal',
                'isPublished' => true,
                'img' => "115.png"
            ],
            [
                'id' => 9,
                'title' => 'Mewtwo',
                'content' => 'Pokemon psy',
                'isPublished' => true,
                'img' => "175px-Mewtwo-DEPS.png"
            ],
            [
                'id' => 10,
                'title' => 'Ronflex',
                'content' => 'Pokemon normal',
                'isPublished' => true,
                'img' => "ronflex.png"
            ]


        ];
    }

    #[Route('/Listpokemon', name: 'ListPokemon')]
    public function ListPokemon()
    {

        return $this->render('ListPokemon.html.twig', [
            'pokemons' => $this->pokemons
        ]);

    }

    #[Route('/Show/{idPokemon}', name: 'ShowPokemon')]
    // Injection de dépendance (ou "autowire"): on demande a symfony
        // de créer une instance de la classe Resquest dans la variable $request
        //public function showPokemon(Request $request)

    public function showPokemon($idPokemon): Response

    {
        //$request = new request($_GET,$_POST);
        //$request = Request::createFromGlobals();

        //$idPokemon= $request->query->get('id');

        $pokemonfound = null;
        foreach ($this->pokemons as $pokemon) {
            if ($pokemon['id'] === (int)$idPokemon) {
                $pokemonfound = $pokemon;
            }
        }
        return $this->render('ShowPokemon.html.twig', [
            'pokemon' => $pokemonfound]);
    }

    #[Route('/ListCategories', name: 'ListCategories')]
    public function Categories()
    {

        $categories = ['Red', 'Green', 'Blue', 'Yellow', 'Gold', 'Silver', 'Crystal'];

        $html = $this->renderview('ListCategories.html.twig', [
            'categories' => $categories]);

        return new Response($html, status: 200);

    }

    #[Route('/PokemonBdd', name: 'PokemonBdd')]
    public function ShowPokemonBdd(PokemonRepository $PokemonRepository)
    {

        $pokemons = $PokemonRepository->findAll();

        return $this->render('PokemonFromBdd.html.twig', [
            'pokemons' => $pokemons]);
    }

    #[Route('/PokemonById/{id}', name: 'PokemonById')]
    public function ShowPokemonById(int $id, PokemonRepository $PokemonById): Response
    {

        $pokemon = $PokemonById->find($id);

        return $this->render('ShowPokemonById.html.twig', [
            'pokemon' => $pokemon
        ]);
    }

    #[Route('/searchPokemon', name: 'PokemonSearch')]
    public function searchPokemon(Request $request, PokemonRepository $pokemonRepository): Response
    {
        //définit un tableau vide par défaut
        $pokemonsFound = [];

        // si dans la requete il y a un nom de rentré
        if ($request->request->has('name')) {

            $nameSearched = $request->request->get('name');
            $pokemonsFound = $pokemonRepository->findLikeName($nameSearched);

            //$pokemonFound= null;
            //if($request->request->has('name')){
            //$searchName = $request->request->get('name');
            //$pokemonFound = $pokemonRepository->findOneBy(['name'=>$searchName]);

// si le pokemon n'existe pas renvoie a une page d'erreur
            if (count($pokemonsFound) === 0) {
                $html = $this->renderView('404.html.twig');
                return new Response($html, status: 404);
            }
        }

        // sinon renvoie les infos du pokemon
        return $this->render('SearchPokemon.html.twig', [
            'pokemons' => $pokemonsFound
        ]);
    }

    #[Route('/pokemons/delete/{id}', name: 'deletePokemon')]
    // je viens inserer un id dans les parametres pour rappeler l'id de la route
    public function deletePokemon(int $id, EntityManagerInterface $EntityManager, Pokemonrepository $pokemonrepository): Response
    {
        // methode find pour recuperer un id
        // repository pour toute les requetes
        // entity pour toute les requetes de modifications
        $pokemon = $pokemonrepository->find($id);

        // j'utilise la classe entity manager pour préparer la requête SQL de suppression
        // cette requête n'est pas executée tout de suite

        if (!$pokemon) {
            $html = $this->renderView('404.html.twig');
            return new Response($html, status: 404);
        }
        $EntityManager->remove($pokemon);
        // j'exécute les requetes SQL présentes
        $EntityManager->flush($pokemon);

        return $this->redirectToRoute('PokemonBdd');
    }

    #[Route('/pokemons/insert/withoutForm', name: 'InsertPokemon')]
    public function InsertPokemon(EntityManagerInterface $entityManager)
    {

        // j'instancie la classe de l'entité Pokemon
        // je remplis toutes ces propriétés (soit avec le constructor, qu'il faut créé, soit avec les setters)
        $pokemon = new Pokemon(
            name: 'Roucool',
            description: "Il est souvent vu dans les forêts. Il brasse l'air de ses ailes près du sol pour projeter du sable.",
            image: 'https://www.pokemon.com/static-assets/content-assets/cms2/img/pokedex/full/016.png',
            type: 'Normal/Vol',);

        // est équivalent a:

        //$pokemon=new pokemon()
        //$pokemon->setname('Roucool')
        //$pokemon->setdescription("Il est souvent vu dans les forêts. Il brasse l'air de ses ailes près du sol pour projeter du sable.")
        //$pokemon->setimage('https://www.pokemon.com/static-assets/content-assets/cms2/img/pokedex/full/016.png')
        //$pokemon->settype('Normal/Vol')


        $entityManager->persist($pokemon);
        // j'exécute les requetes SQL présentes
        $entityManager->flush($pokemon);

        return $this->redirectToRoute('PokemonBdd');
    }
}