<?php

namespace App\Security\Voter;
use App\Entity\Event;
use App\Entity\User;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    const EDIT = "event_edit";
    const DELETE = "event_delete";
    const CREATE_PROMOTE = 'create_promote';
    const EDIT_PROMOTE = 'edit_promote';
    const READ_STATE = "read_state";
    const REGISTRATION = "event_register";
    const DISCLAIMER = "event_disclaimer";
    const ADD_CALENDAR = "add_calendar";

    private Security $security ;
    /**
     * EventVoter constructor.
     * @param Security $security
     */
    public function __construct(Security $security){
        $this->security = $security ;
    }

    /**
     * Vérifier la permission (ou le role) existe dans le voter
     * @param string $attribute
     * @param mixed $event
     * @return bool
     */
    protected function supports(string $attribute, $event): bool
    {
        /*Vérifie que l'attribut passé en paramètre existe dans le tableau
        et qu'on a bien une instance du sujet passé en paramètre*/
        return in_array($attribute, [self::EDIT,self::DELETE, self::CREATE_PROMOTE, self::EDIT_PROMOTE,
                self::READ_STATE,self::REGISTRATION, self::DISCLAIMER, self::ADD_CALENDAR])
            && $event instanceof Event;
    }

    /**
     * Vérifie que tous les critères sont respectés
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return false
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        //Le user qui est actuellement connecté
        $user = $token->getUser();

        //Si l'utilisateur est anonyme,  on n'autorise pas l'accès
        if (!$user instanceof UserInterface) {
            return false;
        }

        $event = $subject;

        //On vérifie que l'évenement est bien rattaché à un utilisateur
        if($event->getUser() === null) return false ;

        //On donne l'accès à l'administateur de toute action
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        //Vérifie les conditions et retourne true pour accorder l'autorisation
        switch ($attribute) {

            case self::EDIT:
                return $this->canEdit($event,$user);

            case self::DELETE :
                return $this->canDelete($event,$user);

            case self::CREATE_PROMOTE :
                return $this->canCreatePromote($event,$user);

            case self::EDIT_PROMOTE :
                return $this->canEditPromote($event,$user);

            case self::READ_STATE :
                return $this->canReadEventStat($event,$user);

            case self::REGISTRATION :
                return $this->canRegistrationEvent($event,$user);

            case self::DISCLAIMER :
                return $this->canDisclaimerEvent($event,$user);

            case self::ADD_CALENDAR :
                return $this->canAddCalendar($event,$user);

        }

        //return false;
        throw new LogicException('Ce code ne doit pas être atteint !');

    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canEdit(Event $event, User $user) : bool
    {
        return $user === $event->getUser();
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canDelete(Event $event, User $user): bool
    {
        return $user === $event->getUser();
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canCreatePromote(Event $event, User $user): bool
    {
        return $user === $event->getUser();
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canEditPromote(Event $event, User $user): bool
    {
        return $user === $event->getUser();
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canReadEventStat(Event $event, User $user): bool
    {
        return $user === $event->getUser();
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canRegistrationEvent(Event $event, User $user): bool
    {
        return !in_array("ROLE_PRO", $user->getRoles());
        return true;
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canDisclaimerEvent(Event $event, User $user): bool
    {
        return !in_array("ROLE_PRO", $user->getRoles());
        return true;
    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canAddCalendar(Event $event, User $user): bool
    {
        return !in_array("ROLE_PRO", $user->getRoles());
        return true;
    }

}