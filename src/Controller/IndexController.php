<?php

// On crée un namespace c'est a dire un chemin pour identifier la classe actuelle
namespace App\Controller;
// On appelle le namespace des classes qu'oon utilise pour que
// symfony fasse le require de ces classes
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


// On &tends la classe AbstractController qui permet d'utiliser des fonctions
// utilitaires pour les controller (twig, etc)
class IndexController extends AbstractController{

    // Permet de créer une route c'est a dire une nouvelle page sur notre appli
    //Quand l'url est appelée cela exécute automatiquement la méthode définie sous la route
#[Route('/', name:'home')]
    public function index(){
        var_dump('value: "salut"');die;
    }
}