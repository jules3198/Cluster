<?php

namespace App\Controller;

use App\Repository\ActivateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/validation")
 */
class ValidationController extends AbstractController
{
    /**
     * @Route("/{token}", name="validation")
     */
    public function index(ActivateRepository $activateRepository,string $token): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $activate = $activateRepository->findOneBy(['token'=>$token]);
        if($activate) {
            $user = $activate->getUser();
            $user->setActive(true);
            $activate->setStatus(1);
            $entityManager->persist($user);
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('all_events');
    }

}
