<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 02.04.19
 * Time: 19:52.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\StampRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function register(AuthenticationUtils $authenticationUtils, Security $security, TokenStorageInterface $tokenStorage, StampRepository $stampRepository, UserPasswordEncoderInterface $passwordEncoder, Request $request)
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

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $entityyManager = $this->getDoctrine()->getManager();
            $entityyManager->persist($user);
            $entityyManager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('register/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
