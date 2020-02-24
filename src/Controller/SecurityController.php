<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 17.09.19
 * Time: 18:46.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\StampRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Security $security, TokenStorageInterface $tokenStorage, StampRepository $stampRepository)
    {
        if ($security->isGranted('ROLE_USER')) {
            $currentUser = $tokenStorage->getToken()
                ->getUser();
            $usersToFollow = [];

            if ($currentUser instanceof User) {
                $stamp = $stampRepository->findAllByUsers(
                    $currentUser->getFollowing()
                );
                $usersToFollow = count($stamp);
            }

            return $this->redirectToRoute('index',
                [
                    'stamps' => $stamp,
                    'usersToFollow' => $usersToFollow,
                ]
            );
        }

        return new Response($this->twig->render(
            'security/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        ));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/templatetest", name="template_test")
     */
    public function templatetest()
    {
        $html = $this->twig->render('base.html.twig');

        return new Response($html);
    }
}
