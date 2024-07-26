<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;


class RouletteController extends AbstractController {
    #[Route('/Roulette', name:'Roulette')]
    public function Roulette(){
        $request=Request::createFromGlobals();

        if(!$request->query->has('age')){
            return $this->render('VerifAge.html.twig');

        }else{
            $age=$request->query->get('age');
            if ($age>=18) {

                return $this->render('Roulette.html.twig');
            }else{
                return $this->render('Accueil.html.twig');}
        }
    }
}

