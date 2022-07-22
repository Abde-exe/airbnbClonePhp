<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category-add', name: 'category_add')]
    public function addCategory(Request $request, ManagerRegistry $doctrine)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', "Veuillez vous connecter pour accéder à cette page");
            return $this->redirectToRoute('app_login');
        }

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $doctrine->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash("success", "Category a bien été ajouté");
            return $this->redirectToRoute("app_home");
        }
        return $this->render("Admin/Users.html.twig", [
            "formCategory" => $form->createView()
        ]);
    }
}
