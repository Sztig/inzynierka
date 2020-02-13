<?php
/**
 * Created by PhpStorm.
 * User: sztig
 * Date: 08.11.19
 * Time: 15:09
 */

namespace App\Controller;

use App\Entity\Stamp;
use App\Entity\User;
use App\Form\StampType;
use App\Repository\StampRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * @Route("/stamp")
 */
class StampController extends AbstractController
{

    /**
     * @Route("/add", name="stamp_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(TokenStorageInterface $storage, Request $request)
    {
        $user = $storage->getToken()->getUser();
        $stamp = new Stamp();
        $stamp->setUser($user);

        $form = $this->createForm(
            StampType::class,
            $stamp
        );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file*/
            $file = $request->files->get('stamp')['file'];
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $filename
            );
            $stamp->setImage($filename);


            $em = $this->getDoctrine()->getManager();
            $em->persist($stamp);
            $em->flush();

            return $this->redirectToRoute('stamp_user',
                array('id' => $stamp->getUser()->getId()));
        }

        return $this->render('stamp/add.html.twig',
            ['form' => $form->createView()
            ]);
    }
    /**
     * @Route("/edit/{id}", name="stamp_edit")
     * @Security("is_granted('edit', stamp)", message="Access denied")
     */
    public function edit(Stamp $stamp, Request $request)
    {
        $form = $this->createForm(
            StampType::class,
            $stamp
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file*/
            $file = $request->files->get('stamp')['file'];
            if($file!=null){
            $uploads_directory = $this->getParameter('uploads_directory');
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $uploads_directory,
                    $filename
                );
                $stamp->setImage($filename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($stamp);
            $em->flush();

            return $this->redirectToRoute('stamp_user',
                array('id' => $stamp->getUser()->getId()));
        }
        return $this->render('stamp/add.html.twig',
            ['form' => $form->createView()
            ]);
    }

    /**
     * @Route("/delete/{id}", name="stamp_delete")
     * @Security("is_granted('delete', stamp)", message="Access denied")
     */
    public function delete(Stamp $stamp)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($stamp);
        $em->flush();

        $this->addFlash('notice', 'stamp was deleted');

        return $this->redirectToRoute('stamp_user',
            array('id' => $stamp->getUser()->getId()));
    }

    /**
     * @Security("is_granted('ROLE_USER')")
     * @Method({"GET", "HEAD"})
     */
    public function feed(TokenStorageInterface $tokenStorage, UserRepository $userRepository, StampRepository $stampRepository){
        $currentUser = $tokenStorage->getToken()
            ->getUser();
        $usersToFollow = [];

        if ($currentUser instanceof User) {
            $stamp = $stampRepository->findAllByUsers(
                $currentUser->getFollowing()
            );
            $usersToFollow = count($stamp);
        }

        return new Response(
            $this->renderView('feed/feed.html.twig',
                [
                    'stamps' => $stamp,
                    'usersToFollow' => $usersToFollow
                ]
                )
        );
    }
}