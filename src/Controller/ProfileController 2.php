<?php

namespace App\Controller;

use App\Entity\UserPhoto;
use App\Form\ProfileType;
use App\Form\UserImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Security\Voter\ProfileVoter;


class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile_show")
     */
    public function index(): Response
    {
        $user=$this->getUser();
        return $this->render('profile/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile_edit", name="profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request)
    {
        $this->denyAccessUnlessGranted(EventVoter::EDIT, $this->getUser());

        $new = false;
        $user=$this->getUser();
        $userImage = $user->getUserProfile();
        if($userImage == null ) {
            $userImage = new UserPhoto();
            $new = true;
        }

        $form = $this->createForm(ProfileType::class, $user);
        $imageForm = $this->createForm(UserImageType::class, $userImage);
        $imageForm->handleRequest($request);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

        }else if($imageForm->isSubmitted() && $imageForm->isValid()) {

            if( $new == true ) {
                $entityManager = $this->getDoctrine()->getManager();
                $userImage->setUserId($user);
                $entityManager->persist($userImage);
                $entityManager->flush();
                return $this->redirectToRoute('profile_show', [
                    'user' => $user,
                ]);
            }
            else {
                $this->getDoctrine()->getManager()->flush();
            }

        }

        return $this->render('profile/edit-profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'imageForm' => $imageForm->createView(),
        ]);
    }
}
