<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 13.02.20
 * Time: 22:12
 */

namespace App\Security;

use App\Entity\Category;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CustomCategoryVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, [self::EDIT, self::DELETE])){
            return false;
        }

        if (!$subject instanceof Category){
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User){
            return false;
        }

        /** @var Category $category */
        $category = $subject;

        return $category->getUser()->getId() === $authenticatedUser->getId();

    }

}