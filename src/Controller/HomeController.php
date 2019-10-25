<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     * @param RecipeRepository $recipeRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(RecipeRepository $recipeRepository, CategoryRepository $categoryRepository)
    {

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'last' => $recipeRepository->findLastRecipes(2),
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
