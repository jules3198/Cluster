<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\EventSearch;
use App\Form\EventSearchType;
use App\Repository\EventRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllEventsController extends AbstractController
{
    /**
     * @Route("/all/events", name="all_events")
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
            7// Nombre de résultats par page
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
     * @Route("/details/event/{id}", name="details_event", methods={"GET"})
     */
    public function show(Event $event): Response
    {

        //Incrémenter le nombre de visite
        $numberOfVisits = $event->getNumberOfVisits();
        $event->setNumberOfVisits($numberOfVisits+1);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('all_events/details_event.html.twig', [
            'event' => $event,
        ]);
    }
}
