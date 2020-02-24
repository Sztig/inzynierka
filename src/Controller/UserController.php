<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 27.01.20
 * Time: 19:31.
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\CollectionRepository;
use App\Repository\StampRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/results", name="user_search")
     */
    public function searchUsers(Request $request, UserRepository $userRepository)
    {
        $query = $request->request->get('_query');
        if ($query) {
            $users = $userRepository->findUserByName($query);
        }

        return $this->render('search/search.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/{id}", name="stamp_user")
     */
    public function userStamps(User $user, StampRepository $stampRepository, CollectionRepository $collectionRepository)
    {
        $stamps = $stampRepository->findAllWithoutCategoryAndCollection($user);
        $publicCollections = $collectionRepository->findAllPublicByUser($user);
        $privateCollections = $collectionRepository->findAllPrivateByUser($user);
        $html = $this->renderView(
            'stamp/raw.html.twig',
            [
                'stamps' => $stamps,
                'categories' => $user->getCategories(),
                'user' => $user,
                'public_collections' => $publicCollections,
                'private_collections' => $privateCollections
            ]
        );

        return new Response($html);
    }
}
