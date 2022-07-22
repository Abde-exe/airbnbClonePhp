<?php

namespace App\Controller;

use App\Data\ParamReservation;
use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Panier;
use DateTime;
use App\Entity\Propriete;
use App\Form\CategoryType;
use App\Form\ParamForm;
use App\Form\ProprieteType;
use App\Form\SearchForm;
use App\Repository\CategoryRepository;
use App\Repository\ProprieteRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProprieteController extends AbstractController
{


    #[Route('/list-prop', name: 'list_prop')]
    public function index(ProprieteRepository $repo, Request $request)
    {
        //filtres recherche
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        //tous les biens
        $proprietes = $repo->findSearch($data);

        return $this->render('propriete/Liste.html.twig', [
            "proprietes" => $proprietes,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/propriete-add', name: 'propriete_add')]
    public function add(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', "Veuillez vous connecter pour accéder à cette page");
            return $this->redirectToRoute('app_login');
        }

        $propriete = new Propriete();

        $form = $this->createForm(ProprieteType::class, $propriete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            // on recupere l'image depuis le formulaire
            $file = $form->get('photos')->getData();
            //dd($file);
            //dd($propriete);
            // le slug permet de modifier une chaine de caractéres : mot clé => mot-cle
            $fileName = $slugger->slug($propriete->getTitre()) . uniqid() . '.' . $file->guessExtension();

            try {
                // on deplace le fichier image recuperé depuis le formulaire dans le dossier parametré dans la partie Parameters du fichier config/service.yaml, avec pour nom $fileName
                $file->move($this->getParameter('photos_proprietes'),  $fileName);
            } catch (FileException $e) {
                // gérer les exeptions en cas d'erreur durant l'upload
            }

            $propriete->setPhotos($fileName);
            $propriete->setUser($user);

            $propriete->setDateEnregistrement(new DateTime("now"));

            $manager = $doctrine->getManager();
            $manager->persist($propriete);
            $manager->flush();
            $this->addFlash("success", "Propriete a bien été ajouté");
            return $this->redirectToRoute("app_home");
        }
        return $this->render("propriete/formulaire.html.twig", [
            "formPropriete" => $form->createView()
        ]);
    }
    #[Route('/propriete-detail/{id}', name: 'propriete_detail')]
    public function voirProp($id, ProprieteRepository $repo, Request $request, SessionInterface $session)
    {
        $data = new ParamReservation();
        $form = $this->createForm(ParamForm::class, $data);
        $form->handleRequest($request);
        // dd($data);
        $propriete = $repo->find($id);
        if ($form->isSubmitted() && $form->isValid()) {
            $panier = new Panier();
            $panier = $session->get('panier', []);
            // if (empty($panier)) {
            $panier = ['propriete' => $propriete, 'dateE' => $data->dateE, 'dateS' => $data->dateS, 'nbPers' => $data->nbPers];
            // } else {
            //     $panier[$id]++;
            // }
            $session->set("panier", $panier);
            //dd($session->get("panier"));
            return $this->redirectToRoute('app_panier');
        }
        return $this->render('propriete/detail.html.twig', [
            'propriete' => $propriete,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/propriete-update/{id}', name: 'propriete_update')]
    public function updateProp($id, Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $propriete = $doctrine->getRepository(Propriete::class)->find($id);
        $form = $this->createForm(ProprieteType::class, $propriete);
        $form->handleRequest($request);

        $image = $propriete->getPhotos();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('photos')->getData()) {
                $imageFile = $form->get('photos')->getData();

                $fileName = $slugger->slug($propriete->getTitre()) . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move($this->getParameter('photos_proprietes'), $fileName);
                } catch (FileException $e) {
                    // gestion des erreur upload
                }
                $propriete->setPhotos($fileName);
            }


            $manager = $doctrine->getManager();
            $manager->persist($propriete);
            $manager->flush();
            $this->addFlash("success", "Propriete modifiée avec succès");

            return $this->redirectToRoute('app_home');
        }
        return $this->render("propriete/formulaire.html.twig", [
            'formPropriete' => $form->createView()
        ]);
    }
    #[Route('/propriete-delete/{id}', name: 'propriete_delete')]
    public function deleteProp($id, ProprieteRepository $repo): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', "Veuillez vous connecter pour accéder à cette page");
            return $this->redirectToRoute('app_login');
        }
        $idUser = $this->getUser();
        $userId = $propriete = $repo->find($id)->getUser();

        if ($idUser != $userId) {
            $this->addFlash('error', "Vous n'etes pas autorisé");
            return $this->redirectToRoute('app_home');
        }

        $propriete = $repo->find($id);
        $repo->remove($propriete, 1);
        $this->addFlash("success", "Propriete a bien été supprimé");

        return $this->redirectToRoute("app_home");
    }
    #[Route('/admin-prop', name: 'admin_proprietes')]
    public function adminProp(ProprieteRepository $repo, CategoryRepository $repoC, Request $request, ManagerRegistry $doctrine)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            $this->addFlash('error', "Veuillez vous connecter pour accéder à cette page");
            return $this->redirectToRoute('app_login');
        }
        $category = new Category();

        $formC = $this->createForm(CategoryType::class, $category);

        $formC->handleRequest($request);
        $proprietes = $repo->findAll();
        $categories = $repoC->findAll();
        if ($formC->isSubmitted() && $formC->isValid()) {

            $manager = $doctrine->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash("success", "Category a bien été ajouté");
            return $this->redirectToRoute("app_home");
        }


        return $this->render('Admin/Proprietes.html.twig', [
            "proprietes" => $proprietes,
            "categories" => $$categories,
            "formCategory" => $formC->createView()
        ]);
    }
}
