<?php

namespace App\Controller;

use App\Entity\Actuality;
use App\Repository\ActualityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/nouvelle-actualite", name="nouvelle_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createActuality()
    {

    }

    /**
     * @Route("/modifier-actualite/{slug}", name="modifier_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function updateActuality()
    {

    }

    /**
     * @Route("/supprimer-actualite/{slug}", name="supprimer_actualite")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteActuality()
    {

    }
}
