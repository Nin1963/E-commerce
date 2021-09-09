<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        dd("Çà fonctionne!");
    }

    /**
     * @Route("/test/{age<d+>?0}", name="test", methods={"GET", "POST"}, host="localhost")
     */
    public function test(Request $request, $age)
    {
        return new Response("Vous avez $age ans");
    }

    
}
