<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Bid;
use App\Entity\Participants;
use App\Form\EventType;
use App\Form\BidType;
use App\Repository\EventRepository;
use App\Repository\BidRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Eluceo\iCal\Component\Calendar;
use Eluceo\iCal\Component\Event as ICalEvent;
use Eluceo\iCal\Property\Event\Geo;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\ConstraintViolation;
use App\Security\Voter\EventVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/dashboard/events")
 */
class EventController extends AbstractController
{

    /**
     * @Route("/index_pro", name="event_index_pro", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function indexPro(EventRepository $eventRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRO', $this->getUser(),
            'Unable to access this page!');

        return $this->render('event/index-pro.html.twig', [
            'events' => $eventRepository->findEventsByPro($this->getUser()),
        ]);
    }

    /**
     * @Route("/index_user", name="event_index_user", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function indexUser(EventRepository $eventRepository): Response
    {
        //$this->denyAccessUnlessGranted(EventVoter::INDEX_USER, $this->getUser());

        return $this->render('event/index-user.html.twig', [
            'events' => $eventRepository->findNext10DaysEvents()
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRO', $this->getUser(),
            'Unable to access this page!');

        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $event->setUser($this->getUser());
            $event->setStatus("Open");

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index_pro');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/past",name="past_event")
     * @param EventRepository $eventRepository
     * @return Response
     */
     public function pastEvents(EventRepository $eventRepository): Response
    {

        return $this->render('event/pasts.html.twig', [
            'events' => $eventRepository->getPastEvents($this->getUser()),
        ]);
    }

    /**
     * @Route("/actual_future",name="actual_future_event")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function actualFutureEvents(EventRepository $eventRepository): Response
    {

        return $this->render('event/actual_future.html.twig', [
            'events' => $eventRepository->getActualEtFutureEventsByPro($this->getUser())
        ]);
    }

    /**
     * Consulter la liste des événements auquels à participé un user
     * @Route("/participation_by_user",name="events_participation_by_user")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function eventsParticipedByUser(EventRepository $eventRepository): Response
    {
        return $this->render('users/EventParticipationByUser.html.twig', [
            'eventsParticipatedByUser' => $eventRepository->findEventParticipationByUser($this->getUser()),
        ]);
    }

    /**
     * Consulter la liste des événements auquels il s'est inscrit qui n'ont pas encore commencé
     * @Route("/registration_pending_by_user",name="events_registration_pending_by_user")
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function eventsRegistrationPendingByUser(EventRepository $eventRepository): Response
    {

        return $this->render('users/EventRegistrationPendingByUser.html.twig', [
            'eventsRegistrationPendingByUser' =>
                $eventRepository->findEventRegistrationByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/top_list", name="event_top_list", methods={"GET"})
     * @param EventRepository $eventRepository
     * @return Response
     */
    public function topList(EventRepository $eventRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_PRO', $this->getUser(),
            'Unable to access this page!');

        return $this->render('event/top_list.html.twig', [
            'eventsTopList' => $eventRepository->getEventsProByTopList()
        ]);
    }

    /**
     * @Route("/{slug}", name="event_show", methods={"GET"})
     * @param Event $event
     * @return Response
     */
    public function show(Event $event): Response
    {
        $numberOfVisits = $event->getNumberOfVisits();
        $event->setNumberOfVisits($numberOfVisits+1);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('event/show.html.twig', [
            'event' => $event
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function delete(Request $request, Event $event): Response
    {

        $this->denyAccessUnlessGranted(EventVoter::DELETE, $event);

        if ($this->isCsrfTokenValid('delete' . $event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index_pro');
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function edit(Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted(EventVoter::EDIT, $event);

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index_pro');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/reserve/{id}", name="event_reserve", methods={"GET"})
     * @param Event $event
     * @return RedirectResponse
     */
     public function reserve(Event $event): RedirectResponse
    {
        $this->denyAccessUnlessGranted(EventVoter::REGISTRATION, $event);
        $participant = new Participants();
        $participant->setUser($this->getUser());
        $participant->setEvent($event);
        $participant->setCreatedAt(new \DateTime('now'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($participant);
        $entityManager->flush();
        return $this->redirectToRoute('event_index_user');
    }

    /**
     * @Route("/desiste/{id}", name="event_desiste")
     * @param Event $event
     * @return RedirectResponse
     */
     public function desiste(Event $event): RedirectResponse
    {
        $this->denyAccessUnlessGranted(EventVoter::DISCLAIMER, $event);

        $event->removeParticipant($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        return $this->redirectToRoute('event_index_user');
    }

    /**
     * @Route("/remove_reservation/{id}/{id_user}", name="event_remove_participant")
     * @param UserRepository $userRepository
     * @param Event $event
     * @param int|null $id_user
     * @return RedirectResponse
     */
    public function removeReservation(UserRepository $userRepository, Event $event, ?int $id_user): RedirectResponse
    {

        $this->denyAccessUnlessGranted(EventVoter::ADD_CALENDAR, $event);

        $user = $userRepository->find($id_user);
        $event->removeParticipant($user);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('event_show', ['id' => $event->getId()]);
    }

    /**
     * @Route("/add_calendar/{id}",name="add_calendar", methods={"GET"})
     * @param Event $event
     * @param HttpClientInterface $client
     */
    public function addCalendar(Event $event, HttpClientInterface $client)
    {
        $vCalendar = new Calendar('13123');
        $iCalEvent = new ICalEvent();

        $iCalEvent->setSummary($event->getName());
        $iCalEvent->setDescription($event->getDescription());
        $iCalEvent->setDtStart($event->getDateStart());
        $iCalEvent->setDtEnd($event->getDateEnd());
        $vCalendar->addComponent($iCalEvent);

        header('Content-Type: text/calendar; charset=utf-8');
        header('Content-Disposition: inline; filename="cal.ics"');
        echo($vCalendar->render());
        exit;
    }

    /**
     * @Route("/stats/{id}",name="event_stats", methods={"GET"})
     * @param Request $request
     * @param Event $event
     * @param EventRepository $eventRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function stats(Request $request, Event $event, EventRepository $eventRepository) :Response
    {
        $this->denyAccessUnlessGranted(EventVoter::READ_STATE, $event);

        $nbReservation = count($eventRepository->getEventStats($event)->getParticipants());

        $nbPlaceRestante = $eventRepository->getEventStats($event)->getNbParticipants();
        $nbPlaceTotale = $nbPlaceRestante + $nbReservation ;

        // Moyenne = nbParticipants / NbParticipants totale * 100
        $moyenneParticipation = intval($nbReservation /$nbPlaceTotale * 100);

        $nbPromotion = 0;
        foreach ( $eventRepository->getEventStats($event)->getBids() as $promotion )
            $nbPromotion = $promotion->getNbPromotion();

        $prixEvent = $eventRepository->getEventStats($event)->getPrice();

        $gainsAttenduEvent = 0;

        $percentBeneficeEvent = 0;
        $percentPerteEvent = 0;

        $gainsObtenuEvent = $percentBeneficeEvent * $gainsAttenduEvent / 100;
        $gainsPerduEvent= $percentPerteEvent * $gainsAttenduEvent / 100;

        $nbVisitsByEvent = $eventRepository->getEventStats($event)->getNumberOfVisits();

        if($prixEvent > 0){

            $gainsAttenduEvent = $prixEvent * $nbPlaceTotale ;

            $percentBeneficeEvent = intval($nbReservation * $prixEvent/$gainsAttenduEvent*100);
            $percentPerteEvent = 100 - $percentBeneficeEvent;

            $gainsObtenuEvent = $percentBeneficeEvent * $gainsAttenduEvent / 100;
            $gainsPerduEvent= $percentPerteEvent * $gainsAttenduEvent / 100;
        }

        return $this->render('statistiques/stats.html.twig', [
            'nbReservation' => json_encode($nbReservation),
            'MoyenneParticipation' => json_encode($moyenneParticipation),
            'nbPromotion' => json_encode($nbPromotion),
            'percentBeneficeEvent' => json_encode($percentBeneficeEvent),
            'percentPerteEvent' => json_encode($percentPerteEvent),
            'gainsAttenduEvent' => json_encode($gainsAttenduEvent),
            'gainsObetnuEvent' => json_encode($gainsObtenuEvent),
            'gainsPerduEvent' => json_encode($gainsPerduEvent),
            'nbVisits' => json_encode($nbVisitsByEvent),
            'event' => $event // C'est utile pour l'utilisation du is_granted() dans la vue statistique
        ]);
    }

    /**
     * @Route("/promote/{id}", name="event_new_promote", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function promoteNewEvent(Request $request, Event $event): Response
    {
        $this->denyAccessUnlessGranted(EventVoter::CREATE_PROMOTE,$event,
            "Access interdict");

        $bid = new Bid();

        $form = $this->createForm(BidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $nbPromotion = 0;
            $bid->setProfessional($this->getUser())
                ->setNbPromotion($nbPromotion + 1)
                ->setEvent($event)
                ->setCreatedAt(new \DateTime());

            $arrayBackUpPromotion = [] ;
            $arrayBackUpPromotion = $bid->getBackupPromotion();
            array_push($arrayBackUpPromotion,$bid->getCapital());
            $bid->setBackupPromotion($arrayBackUpPromotion);

            $entityManager->persist($bid);
            $entityManager->flush();

            return $this->redirectToRoute('event_index_pro');
        }

        return $this->render('event/promote.html.twig', [
            'bid' => $bid,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/promote/{id}/edit", name="event_edit_promote", methods={"GET","POST"})
     * @param Request $request
     * @param Event $event
     * @param BidRepository $bidRepository
     * @return Response
     * @throws NonUniqueResultException
     */
    public function promoteEditEvent(Request $request, Event $event, BidRepository $bidRepository): Response
    {
        $this->denyAccessUnlessGranted(EventVoter::EDIT_PROMOTE,$event,
            "Access interdict");

        $bid = $bidRepository->findCurrentBid($event);

        $form = $this->createForm(BidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $nbPromotion = $bid->getNbPromotion() + 1 ;
            $bid->setNbPromotion($nbPromotion);

            $bid->setUpdatedAt(new \DateTime());

            $arrayBackUpPromotion = [] ;
            $arrayBackUpPromotion = $bid->getBackupPromotion();
            array_push($arrayBackUpPromotion,$bid->getCapital());
            $bid->setBackupPromotion($arrayBackUpPromotion);

            $entityManager->flush();

            return $this->redirectToRoute('event_index_pro');
        }

        return $this->render('event/promote.html.twig', [
            'bid' => $bid,
            'form' => $form->createView(),
        ]);
    }
}