<?php

namespace App\Controller;

use App\Entity\Player;
use App\Form\SellBuyType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PlayerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/sell-buy', name: 'app_sell_buy')]
    public function sellBuy(Request $request): Response
    {
        $form = $this->createForm(SellBuyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $data           = $form->getData();
            $player         = $data['player'];
            $sellingTeam    = $data['selling_team'];
            $buyingTeam     = $data['buying_team'];
            $amount         = $data['amount'];

            // Mettre à jour les propriétés appropriées des équipes et du joueur
            $player->setTeam($buyingTeam);
            $sellingTeam->removePlayer($player);
            $buyingTeam->addPlayer($player);
            $buyingTeam->setSilverSale($buyingTeam->getSilverSale() - $amount);
            $sellingTeam->setSilverSale($sellingTeam->getSilverSale() + $amount);

            // Enregistrer les modifications en base de données
            $this->entityManager->persist($sellingTeam);
            $this->entityManager->flush();

            $this->addFlash('success', 'La vente/achat a été effectuée avec succès.');

            return $this->redirectToRoute('app_team');
        }

        return $this->render('player/sell_buy.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}