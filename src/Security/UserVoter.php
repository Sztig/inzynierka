<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 27.01.20
 * Time: 19:25.
 */

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        if (!$subject instanceof User) {
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

        /** @var User $user */
        $user = $subject;

        return $user->getId() === $authenticatedUser->getId();
    }
}
