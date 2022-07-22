<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\ReservationDetail;
use App\Repository\ProprieteRepository;
use App\Repository\ReservationDetailRepository;
use App\Repository\ReservationRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
    #[Route('/reserver', name: 'reserver')]
    public function reserver(SessionInterface $session, ReservationRepository $repoRes, ReservationDetailRepository $repoDet, ProprieteRepository $repo, ManagerRegistry $doctrine)
    {
        $reservation = new Reservation();
        $panier = $session->get('panier', []);
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash("error", "Veuillez vous connecter ou vous inscrire pour passer reservation");
            return $this->redirectToRoute("app_login");
        }
        // $dataPanier = [];
        // $total = 0;
        // $repoRes->add($reservation);

        // foreach ($panier as $id => $nbPers) {
        //     $propriete = $repo->find($id);
        //     $dataPanier[] = [
        //         "propriete" => $propriete,
        //         "nbPers" => $nbPers,
        //         "sous-total" => $propriete * $nbPers
        //     ];
        //     $total += $propriete->getPrixJournalier() * $nbPers;
        // }
        // $reservation->setUser($user)
        //     ->setDate(new DateTime("now"))
        //     ->setMontant($total);

        // $repoRes->add($reservation);
        // foreach ($dataPanier as $key => $value) {
        //     $reservationDetail = new ReservationDetail();
        //     $propriete = $value["propriete"];
        //     $nbPers = $value["nbPers"];
        //     $sousTotal = $value["sousTotal"];
        //     $reservationDetail->setReservation($reservation)
        //         ->setPropriete($propriete)
        //         ->setNbPers($nbPers)
        //         ->setPrix($sousTotal);
        //     $repoDet->add($reservationDetail);
        // }
        // $manager = $doctrine->getManager();
        // // $manager->persist();
        // $manager->flush();
        $session->remove('panier');
        $this->addFlash("success", "reservation avec succès");

        return  $this->redirectToRoute("app_home");
    }
}
