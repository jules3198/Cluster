<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Participants;
use App\Entity\User;
use App\Entity\EventSearch;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
use App\Repository\ParticipantsRepository;
use App\Security\Voter\EventVoter;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AllEventsController extends AbstractController
{
    /**
     * @Route("/", name="all_events")
     */
    public function index(EventRepository  $eventRepository, PaginatorInterface $paginator,Request $request): Response
    {
        $user=$this->getUser();
        $search = new EventSearch();
        $form = $this->createForm(EventSearchType::class,$search);
        $donnees = $eventRepository->getCurrentActiveEvents($search);
        $form->handleRequest($request);
        $events = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            12// Nombre de résultats par page
        );
        if($form->isSubmitted()){
            $search= $form->getData();
            $donnees = $eventRepository->getCurrentActiveEvents($search);
            $events = $paginator->paginate(
                $donnees,
                $request->query->getInt('page', 1),
                7// Nombre de résultats par page
            );
        }
        return $this->render('all_events/index.html.twig', [
            'events' => $events,
            'formSearch' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/details/event/{slug}", name="details_event", methods={"GET"})
     */
    public function show(Event $event,ParticipantsRepository $inscriptionRepository): Response
    {
        $user=$this->getUser();
        //Incrémenter le nombre de visite
        $numberOfVisits = $event->getNumberOfVisits();
        $event->setNumberOfVisits($numberOfVisits+1);
        $this->getDoctrine()->getManager()->flush();
        $already = false;
        $creationDate = null;
        $participationId = null;
        if($user) {
            $result = $inscriptionRepository->checkIfInscription($event->getId(),$user->getId());
            if(!empty($result)) {
                $already = true;
                $creationDate = $result[0]->getCreatedAt();
                $participationId = $result[0]->getId();
            }
        }
        return $this->render('all_events/details_event.html.twig', [
            'event' => $event,
            'user' => $user,
            'inscription' => $already,
            'inscriptionDate' => $creationDate,
            'participationId' => $participationId
        ]);
    }

    /**
     * @Route("/inscription/{event}/{user}", name="inscription", methods={"GET"})
     */
    public function inscript(Event $event,User $user,ParticipantsRepository $inscriptionRepository): Response
    {
        $this->denyAccessUnlessGranted(EventVoter::REGISTRATION, $event);

        $result = $inscriptionRepository->checkIfInscription($event->getId(),$user->getId());
        if(empty($result)) {
            $inscription = new Participants();
            $inscription->setEvent($event);
            $inscription->setUser($user);
            $date = new DateTime('now');
            $inscription->setCreatedAt($date);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($inscription);
            $entityManager->flush();
            $this->addFlash("success","inscription réussie");
        }else {
            $this->addFlash("exist","vous êtes déja inscrit");
        }
        return $this->redirectToRoute('details_event', [
            'slug' => $event->getSlug()
        ]);
    }

    /**
     * @Route("/desist/{event}/{participant}", name="delete_inscription", methods={"GET"})
     */
    public function desist(Participants $participant,Event $event,ParticipantsRepository $inscriptionRepository): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($participant);
        $em->flush();
        $this->addFlash("desist","vous venez de vous désinscrire pour cet évenement");
        return $this->redirectToRoute('details_event', [
            'slug' => $event->getSlug()
        ]);
    }


}
