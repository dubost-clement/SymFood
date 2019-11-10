<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Profile;
use App\Form\ProfileType;
use App\Form\EmailUpdateType;
use App\Form\RegistrationType;
use App\Repository\RecipeRepository;
use App\Repository\ProfileRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * Affiche la page du dashboard
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

    /**
     * Affiche la page des informations de l'utilisateur
     * @Route("/mon-compte", name="mon_compte")
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param ObjectManager $manager
     * @param ProfileRepository $profileRepository
     * @return Response
     */
    public function userProfile(Request $request, ObjectManager $manager, ProfileRepository $profileRepository)
    {
        $actualUser = $this->getUser();
        $form = $this->createForm(EmailUpdateType::class, $actualUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($actualUser);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre adresse email a bien été modifiée."
            );

            return $this->redirectToRoute("mon_compte");
        }

        $profile = $profileRepository->findOneBy(['user' => $actualUser]);
        $profileForm = $this->createForm(ProfileType::class, $profile);

        if ($profile === null) {
            $profile = new Profile();
            $profile->setUser($this->getUser());
            $profileForm = $this->createForm(ProfileType::class, $profile);
        }

        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $manager->persist($profile);
            $manager->flush();
            
            $this->addFlash(
                'success',
                "votre photo de profil a bien été modifiée."
            );

            return $this->redirectToRoute("mon_compte");
        }

        return $this->render('account/userProfile.html.twig', [
            'user' => $actualUser,
            'form' => $form->createView(),
            'profile' => $profileForm->createView(),
        ]);
    }
}
