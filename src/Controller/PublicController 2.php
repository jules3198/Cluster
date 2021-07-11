<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicController extends AbstractController
{


    /**
     * @Route("/about-us", name="aboutus")
     */
    public function aboutus(): Response
    {
        return $this->render('front/aboutus.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }
}
