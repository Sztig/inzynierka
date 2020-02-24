<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 23.02.20
 * Time: 18:05
 */

namespace App\Security;

use App\Entity\Collection;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CollectionVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';
    const VIEW = 'view';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])) {
            return false;
        }

        if (!$subject instanceof Collection) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User) {
            return false;
        }

        /** @var Collection $collection */
        $collection = $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView($collection, $authenticatedUser);
            case self::EDIT:
                return $collection->getUser()->getId() === $authenticatedUser->getId();
            case self::DELETE:
                return $collection->getUser()->getId() === $authenticatedUser->getId();
        }
    }

    private function canView(Collection $collection, User $authenticatedUser)
    {
        if ($this->canEdit($collection, $authenticatedUser)) {
            return true;
        }
    }

    private function canEdit(Collection $collection, User $authenticatedUser)
    {
        return $collection->getUser()->getId() === $authenticatedUser->getId();
    }
}