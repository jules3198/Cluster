<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfileVoter extends Voter
{
    const EDIT = "profile_edit";

    private Security $security ;
    /**
     * ProfileVoter constructor.
     * @param Security $security
     */
    public function __construct(Security $security){
        $this->security = $security ;
    }

    /**
     * Vérifier la permission (ou le role) existe dans le voter
     * @param string $attribute
     * @param mixed $user
     * @return bool
     */
    protected function supports(string $attribute, $user): bool
    {
        /*Vérifie que l'attribut passé en paramètre existe dans le tableau
        et qu'on a bien une instance du sujet passé en paramètre*/
        return in_array($attribute, [self::EDIT])
            && $user instanceof User;
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
        $subject = $this->security->getUser();

        //On vérifie que le user est bien rattaché à un compte
        if($subject === null) return false ;

        //On donne l'accès à l'administateur de toute action
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        //Vérifie les conditions et retourne true pour accorder l'autorisation
        switch ($attribute) {
            case self::EDIT:
                return $this->canEditProfile($subject);
        }

        throw new LogicException('Ce code ne doit pas être atteint !');

    }

    /**
     * @param Event $event
     * @param User $user
     * @return bool
     */
    private function canEditProfile(User $user) : bool
    {
        return $user === $this->security->getUser();
    }
}
