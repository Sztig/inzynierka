<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 08.04.19
 * Time: 00:31.
 */

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route()
 */
class FollowingController extends Controller
{
    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->follow($userToFollow);

            $this->getDoctrine()
                ->getManager()
                ->flush();
        }

        return $this->redirectToRoute('stamp_user',
            ['id' => $currentUser->getId()]);
    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();
        $currentUser->getFollowing()
            ->removeElement($userToUnfollow);

        $this->getDoctrine()
            ->getManager()
            ->flush();

        return $this->redirectToRoute('stamp_user',
            ['id' => $currentUser->getId()]);
    }
}
