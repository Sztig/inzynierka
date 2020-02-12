<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 27.01.20
 * Time: 19:31
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/{id}", name="stamp_user")
     */
    public function userStamps(User $userWithStamps)
    {
        $html = $this->renderView(
            'stamp/raw.html.twig',
            [
                'stamps' => $userWithStamps->getStamps()
            ]
        );

        return new Response($html);
    }
}