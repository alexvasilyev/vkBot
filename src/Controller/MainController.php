<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller
{
    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        return new Response('b21de22c');
    }
}
