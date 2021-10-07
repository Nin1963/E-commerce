<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CategoryController extends AbstractController
{
    protected $categoryRepository;

    public function __constuct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function renderMenuList() {
       $categories = $this->categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/category/create", name="category_create")
     */
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em) {

        $category = new Category;

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower(($slugger->slug($category->getName()))));

            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('category/create.html.twig', [
            'formView' => $formView
        ]);
        
    }

    /**
     * @Route("admin/category/{id}/edit", name="category_edit")
     */
    public function edit($id, CategoryRepository $categoryRepository,Request $request, SluggerInterface $slugger,EntityManagerInterface $em, Security $security) {

        // $this->denyAccessUnlessGranted("ROLE_ADMIN", null, "Vous n'avez pas le droit d'accéder à cette ressource");

        // $user = $this->getUser();

        // if($user === null) {
        //     return $this->redirectToRoute('security_login');
        // }

        // if($this->isGranted("ROLE_ADMIN") === false) {
        //     throw new AccessDeniedHttpException("Vous n'avez pas le droit d'acceder à cette ressource");
        // }

        $category = $categoryRepository->find($id);

        if(!$category) {
            throw new NotFoundHttpException("Cette carégorie n'existe pas");
        }

        // $user = $this->getUser();// $security->getUser()

        // if(!$user) {
        //     return $this->redirectToRoute("security_login");
        // }

        // if($user !== $category->getOwner()) {
        //     throw new AccessDeniedException("Vous n'êtes pas le propriétaire de cette catégorie");
        // }

        // $this->denyAccessUnlessGranted('CAN_EDIT', $category->getId(), "Vous n'êtes pas le propriétaire de cette catégorie");

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower(($slugger->slug($category->getName()))));
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
