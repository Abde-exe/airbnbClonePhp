<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Repository\ProprieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * #[Route('panier', name: 'panier_')]
 */
class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, ProprieteRepository $repo, Request $request): Response
    {

        $panier = $session->get('panier', []);
        if (!empty($panier)) {

            $diffDate = date_diff($panier['dateE'], $panier['dateS']);
            $prixJournalier = $panier['propriete']->getPrixJournalier();
            $nbPers = $panier['nbPers'];
            $prixTotal = $diffDate->days * $nbPers  * $prixJournalier;

            // dd($dataPanier);
            return $this->render('panier/index.html.twig', [
                'nbNuits' => $diffDate->days,
                'propriete' => $panier['propriete'],
                'nbPers' => $nbPers,
                'prixTotal' => $prixTotal
            ]);
        }
        return $this->render('panier/index.html.twig');
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
