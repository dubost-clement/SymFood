<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ActualityController extends AbstractController
{
    /**
     * @Route("/actualités", name="actualites")
     */
    public function index()
    {
        return $this->render('actuality/index.html.twig', [
            'controller_name' => 'ActualityController',
        ]);
    }
}
