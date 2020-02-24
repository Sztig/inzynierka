<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 11.11.19
 * Time: 15:35.
 */

namespace App\Security;

use App\Entity\Stamp;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class StampVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Stamp) {
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

        /** @var Stamp $stamp */
        $stamp = $subject;

        return $stamp->getUser()->getId() === $authenticatedUser->getId();
    }
}
