<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Form\CategoryType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/add", name="category_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(TokenStorageInterface $storage, Request $request)
    {
        $user = $storage->getToken()->getUser();
        $category = new Category();
        $category->setUser($user);

        $form = $this->createForm(
            CategoryType::class,
            $category
        );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('stamp_user',
                array('id' => $category->getUser()->getId()));
        }

        return $this->render('category/index.html.twig',
            ['form' => $form->createView()
            ]);
    }

    /**
     * @Route("/{id}", name="category_stamps" )
     */
    public function showCategoryStamps($id)
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->find($id);

        $stamp = $category->getStamp();

        $html = $this->render(
            'category/raw.html.twig',
            [
                'stamps' => $stamp,
            ]
        );

        return $html;
    }

    /**
     * @Route("/edit/{id}", name="category_edit")
     * @Security("is_granted('edit', category)", message="Access denied")
     */
    public function edit(Category $category, Request $request)
    {
        $form = $this->createForm(
            CategoryType::class,
            $category
        );
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('stamp_user',
                array('id' => $category->getUser()->getId()));
        }

        return $this->render('category/index.html.twig',
            ['form' => $form->createView()
            ]);
    }

    /**
     * @Route("/delete/{id}", name="category_delete")
     * @Security("is_granted('delete', category)", message="Access denied")
     */
    public function delete(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash('notice', 'category was deleted');

        return $this->redirectToRoute('stamp_user',
            array('id' => $category->getUser()->getId()));
    }

}
