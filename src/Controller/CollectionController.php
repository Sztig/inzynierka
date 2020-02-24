<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 23.02.20
 * Time: 15:50
 */

namespace App\Controller;


use App\Entity\Collection;
use App\Entity\User;
use App\Form\CollectionType;
use App\Repository\StampRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @Route("/collections")
 */
class CollectionController extends AbstractController
{
    /**
     * @Route("/add", name="collection_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(TokenStorageInterface $storage, Request $request)
    {
        $user = $storage->getToken()->getUser();
        $collection = new Collection();
        $collection->setUser($user);

        $form = $this->createForm(
            CollectionType::class,
            $collection
        );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();

            return $this->redirectToRoute('stamp_user',
                ['id' => $collection->getUser()->getId()]);
        }

        return $this->render('collection/index.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}", name="collection_stamps" )
     */
    public function showCollectionStamps($id, TokenStorageInterface $tokenStorage, StampRepository $stampRepository)
    {
        $collection = $this->getDoctrine()
            ->getRepository(Collection::class)
            ->find($id);

        $stamp = $collection->getStamp();
        $collectionUserId = $collection->getUser();
        $collectionStatus = $collection->getStatus();
        $currentUser = $tokenStorage->getToken()->getUser();

        if ($collectionStatus === 'private' && $collectionUserId != $currentUser) {
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
        
        $html = $this->render( 'collection/raw.html.twig',
            [
                'stamps' => $stamp,
                'id' => $id,
                'collection' => $collection,
            ]
        );
        
        return $html;
    }

    /**
     * @Route("/edit/{id}", name="collection_edit")
     * @Security("is_granted('edit', collection)", message="Access denied")
     */
    public function edit(Collection $collection, Request $request)
    {
        $form = $this->createForm(
            CollectionType::class,
            $collection
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($collection);
            $em->flush();

            return $this->redirectToRoute('stamp_user',
                ['id' => $collection->getUser()->getId()]);
        }

        return $this->render('collection/index.html.twig',
            ['form' => $form->createView(),
            ]);
    }

    /**
     * @Route("/delete/{id}", name="collection_delete")
     * @Security("is_granted('delete', collection)", message="Access denied")
     */
    public function delete(Collection $collection)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($collection);
        $em->flush();

        $this->addFlash('notice', 'collection was deleted');

        return $this->redirectToRoute('stamp_user',
            ['id' => $collection->getUser()->getId()]);
    }

}