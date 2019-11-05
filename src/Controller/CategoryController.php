<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/gestion-categorie", name="gestion_categorie")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function categoryManagement(CategoryRepository $categoryRepository)
    {
        return $this->render('category/categoryManagement.html.twig', [
            'controller_name' => 'CategoryController',
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/nouvelle-categorie", name="nouvelle_categorie")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     */
    public function createCategory(Request $request, ObjectManager $manager)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("gestion_categorie");
        }

        return $this->render('category/createCategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-categorie/{slug}", name="modifier_categorie")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Category $category
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function updateCategory(Category $category, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute("gestion_categorie");
        }

        return $this->render('category/updateCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimer-categorie/{slug}", name="supprimer_categorie")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Category $category
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function deleteCategory(Category $category, ObjectManager $manager)
    {
        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute("gestion_categorie");
    }
}
