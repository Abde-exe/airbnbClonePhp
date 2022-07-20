<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\PropertySearch;
use App\Entity\Propriete;
use App\Form\PropertySearchType;
use App\Form\SearchForm;
use App\Repository\ProprieteRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProprieteRepository $repo, Request $request)
    {
        //filtres recherche
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        //tous les biens
        $proprietes = $repo->findSearch($data);

        return $this->render('Home.html.twig', [
            "proprietes" => $proprietes,
            'form' => $form->createView(),
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
