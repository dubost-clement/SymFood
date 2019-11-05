<?php

namespace App\Controller;

use App\Entity\Actuality;
use App\Form\ActualityType;
use App\Repository\ActualityRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActualityController extends AbstractController
{
    /**
     * @Route("/actualités", name="actualites")
     * @param ActualityRepository $actualityRepository
     * @return Response
     */
    public function getActualities(ActualityRepository $actualityRepository)
    {
        return $this->render('actuality/actualities.html.twig', [
            'controller_name' => 'ActualityController',
            'actualities' => $actualityRepository->findAll()
        ]);
    }

    /**
     * @Route("/actualité/{slug}", name="actualite")
     * @param Actuality $actuality
     * @return Response
     */
    public function getActuality(Actuality $actuality)
    {
        return $this->render('actuality/actuality.html.twig', [
            'controller_name' => 'ActualityController',
            'actuality' => $actuality
        ]);
    }

    /**
     * @Route("/gestion-actualite", name="gestion_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param ActualityRepository $actualityRepository
     * @return Response
     */
    public function actualityManagement(ActualityRepository $actualityRepository)
    {
       return $this->render('actuality/actualityManagement.html.twig', [
           'actualities' => $actualityRepository->findAll()
       ]);
    }

    /**
     * @Route("/nouvelle-actualite", name="nouvelle_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Request $request
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     */
    public function createActuality(Request $request, ObjectManager $manager)
    {
        $actuality = new Actuality();
        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($actuality);
            $manager->flush();

            return $this->redirectToRoute("gestion_actualite");
        }

        return $this->render('actuality/createActuality.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/modifier-actualite/{slug}", name="modifier_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Actuality $actuality
     * @param Request $request
     * @param ObjectManager $manager
     * @return RedirectResponse|Response
     */
    public function updateActuality(Actuality $actuality, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(ActualityType::class, $actuality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($actuality);
            $manager->flush();

            return $this->redirectToRoute("gestion_actualite");
        }

        return $this->render('actuality/updateActuality.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/supprimer-actualite/{slug}", name="supprimer_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     * @param Actuality $actuality
     * @param ObjectManager $manager
     * @return RedirectResponse
     */
    public function deleteActuality(Actuality $actuality, ObjectManager $manager)
    {
        $manager->remove($actuality);
        $manager->flush();

        return $this->redirectToRoute("gestion_actualite");
    }
}
