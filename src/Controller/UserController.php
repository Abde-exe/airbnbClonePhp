<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Propriete;
use App\Form\CategoryType;
use App\Repository\ProprieteRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/profil/{id}', name: 'app_profil')]
    public function afficherProfil(ManagerRegistry $doctrine, $id, UserRepository $repo)
    {
        $user = $repo->find($id);
        $proprietes = $doctrine->getRepository(Propriete::class)->findBy(["User" => $id]);

        return $this->render('Profil.html.twig', [
            "proprietes" => $proprietes,
            "user" => $user,

        ]);
    }

    #[Route('/admin/{id}', name: 'app_admin')]
    public function afficherAdmin(ManagerRegistry $doctrine, $id, UserRepository $repo, ProprieteRepository $propRepo)
    {
        $user = $repo->find($id);
        $users = $repo->findAll();
        $proprietes = $propRepo->findAll();

        return $this->render('Admin/Admin.html.twig', [
            "proprietes" => $proprietes,
            "users" => $users,
            "user" => $user,

        ]);
    }

    #[Route('/admin-users', name: 'admin_users')]
    public function adminUsers(UserRepository $repo, Request $request)
    {
        $users = $repo->findAll();

        return $this->render('Admin/Users.html.twig', [
            "users" => $users,
        ]);
    }

    #[Route('/user-delete/{id}', name: 'user_delete')]
    public function deleteUser($id, UserRepository $repo): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', "Veuillez vous connecter pour accéder à cette page");
            return $this->redirectToRoute('app_login');
        }


        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', "Vous n'etes pas autorisé");
            return $this->redirectToRoute('app_home');
        }

        $user = $repo->find($id);
        $repo->remove($user, 1);
        $this->addFlash("success", "User a bien été supprimé");

        return $this->redirectToRoute("app_home");
    }
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
