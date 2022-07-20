<?php

namespace App\Controller;

use App\Entity\Propriete;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine)
    {
        $proprietes = $doctrine->getRepository(Propriete::class)->findAll();

        return $this->render('Home.html.twig', [
            "proprietes" => $proprietes
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
}
