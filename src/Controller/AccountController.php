<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\RecipeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Affiche la page d'inscription des utilisateurs
     * @Route("/inscription", name="inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param ObjectManager $manager
     * @return Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        $user = New User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute("app_login");
        }

        return $this->render('account/inscription.html.twig', [
            'controller_name' => 'AccountController',
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/dashboard", name="dashboard")
     * @Security("is_granted('ROLE_USER')")
     * @param RecipeRepository $recipeRepository
     * @return Response
     */
    public function userDashboard(RecipeRepository $recipeRepository)
    {
        $currentUser = $this->getUser();

        return $this->render('account/userDashboard.html.twig', [
            'controller_name' => 'AccountController',
            'userName' => $currentUser->getFirstName(),
            'recipes' => $recipeRepository->findRecipesByUser($currentUser->getId())
        ]);
    }
}
