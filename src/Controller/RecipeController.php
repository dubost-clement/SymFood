<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    /**
     * Affiche la liste de toutes les recettes
     * @Route("/recettes", name="recettes")
     * @param RecipeRepository $recipeRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function getRecipes(RecipeRepository $recipeRepository, CategoryRepository $categoryRepository)
    {
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipeRepository->findAll(),
            'categories' =>$categoryRepository->findAll()
        ]);
    }

    /**
     * Affiche une recette
     * @Route("/recette/{slug}", name="recette")
     * @param Recipe $recipe
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    public function getRecipe(Recipe $recipe, RecipeRepository $recipeRepository)
    {
        $recipeId = $recipe->getCategory()->getId();

        return $this->render('recipe/recipe.html.twig', [
            'recipe' => $recipe,
            'suggestions' => $recipeRepository->findSuggestionByCategory($recipeId)
        ]);
    }

    /**
     * Affiche la liste de toutes les recettes d'une catégorie
     * @Route("/recettes/categorie/{categorie}", name="recettes_categorie")
     * @param $categorie
     * @param RecipeRepository $recipeRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function geRecipeByCategory($categorie, RecipeRepository $recipeRepository, CategoryRepository $categoryRepository)
    {
        $categoryTitle = $categoryRepository->findOneBy([
            'title' => $categorie
        ]);

        $categoryId = $categoryTitle->getId();

        return $this->render('recipe/recipesByCategory.html.twig', [
            'recipesByCategory' => $recipeRepository->findRecipesByCategory($categoryId),
            'categoryTitle' => $categoryTitle->getTitle()
        ]);
    }

    /**
     * Affiche la création d'une recette
     * @Route("/nouvelle-recette", name="nouvelle_recette")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function createRecipe(Request $request, ObjectManager $manager)
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            return $this->redirectToRoute("dashboard");
        }

        return $this->render('recipe/newRecipe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche la modification d'une recette
     * @Route("/modifier-recette/{slug}", name="modifier_recette")
     * @Security("is_granted('ROLE_USER')")
     * @param Recipe $recipe
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function updateRecipe(Recipe $recipe, Request $request, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('EDIT', $recipe);

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($recipe);
            $manager->flush();

            return $this->redirectToRoute("dashboard");
        }

        return $this->render('recipe/updateRecipe.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Supprime une recette
     * @Route("/supprimer-recette/{slug}", name="supprimer_recette")
     * @Security("is_granted('ROLE_USER')")
     * @return Response
     */
    public function deleteRecipe(Recipe $recipe, ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('DELETE', $recipe);

        $manager->remove($recipe);
        $manager->flush();

        return $this->redirectToRoute("dashboard");
    }
}
