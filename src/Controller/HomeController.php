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

        $proprietes = $repo->findAll();

        return $this->render('Home.html.twig', [
            "proprietes" => $proprietes,
        ]);
    }
}
