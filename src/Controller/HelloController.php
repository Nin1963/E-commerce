<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Twig\Environment;

class HelloController {

    protected $calculator;

    public function __construct(calculator $calculator) {
        $this-> calculator = $calculator;
    }
    

    /**
     * @Route("/hello/{prenom}", name="hello")
     */

    public function hello($prenom = "World", LoggerInterface $logger, Calculator $calculator, Slugify $slugify, Environment $twig) {
        
        dump($twig);

        dump($slugify->slugify("Hello World"));

        $logger->error("mon message de log!");

        $tva = $calculator->calcul(100);
        dump($tva);

        return new Response("Hello $prenom");
    }
}