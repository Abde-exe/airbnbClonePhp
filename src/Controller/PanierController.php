<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\ProprieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * #[Route('panier', name: 'panier_')]
 */
class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProprieteRepository $repo): Response
    {
        $panier = $session->get('panier', []);
        $dataPanier = [];
        $total = 0;
        foreach ($panier as $id => $nbPers) {
            $propriete = $repo->find($id);
            $dataPanier[] = [
                "propriete" => $propriete,
                "nbPers" => $nbPers
            ];
            $total += $propriete->getPrixJournalier() * $nbPers;
        }
        // dd($dataPanier);
        return $this->render('panier/index.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total
        ]);
    }
    #[Route('/add/{id}', name: 'add_panier')]
    public function add($id, SessionInterface $session)
    {
        $panier = new Panier();
        $panier = $session->get('panier', []);
        if (empty($panier)) {
            $panier[$id] = 1;
        } else {
            $panier[$id]++;
        }
        $session->set("panier", $panier);
        dd($session->get("panier"));
    }
    #[Route('/delete/{id}', name: 'supprimer_panier')]
    public function delete($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        } else {
            $this->addFlash("error", "La propriete n'existe pas dans le panier");
            return $this->redirectToRoute("app_panier");
        }
        $session->set("panier", $panier);
        $this->addFlash("success", "Propriete retirée du parnier");
        return $this->redirectToRoute("app_panier");
    }
}
