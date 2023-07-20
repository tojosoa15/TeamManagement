<?php

namespace App\Controller;

use App\Entity\Team;
use App\Form\TeamType;
use App\Form\PlayerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class TeamController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/teams', name: 'app_team')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $repository = $this->entityManager->getRepository(Team::class);
        
        /*// Récupérer toutes les équipes avec pagination de 10 éléments par page
        $pagination = $paginator->paginate(
            $repository->createQueryBuilder('t')->getQuery(),
            $request->query->getInt('page', 1),
            10 
        );*/
        
        // Récupérer toutes les équipes avec pagination de 10 éléments par page
        $pagination = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        // Regrouper les joueurs par équipe
        $teams = [];
        foreach ($pagination as $team) {
            $players = [];
            $teamPlayers = $team->getPlayers();
            foreach ($teamPlayers as $player) {
                $players[] = $player->getFirsName() . ' ' . $player->getName();
            }
            $teams[] = [
                'team' => $team,
                'players' => $players
            ];
        }


        return $this->render('team/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/add-team', name: 'add_team')]
    public function addTeam(Request $request): Response
    {
        $team = new Team();

        $form = $this->createForm(TeamType::class, $team);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données du formulaire
            $teamData = $form->getData();

            // Ajouter l'équipe à la base de données
            $this->entityManager->persist($teamData);
            
            // Traiter les joueurs
            foreach ($teamData->getPlayers() as $player) {
                // Définir la relation bidirectionnelle avec l'équipe
                $player->setTeam($teamData);

                // Ajouter les joueurs à la base de données
                $this->entityManager->persist($player);
            }

            // Exécuter les opérations en base de données
            $this->entityManager->flush();

            $this->addFlash('success', 'L\'équipe a été ajoutée avec succès.');

            return $this->redirectToRoute('app_team');
        }

        return $this->render('team/add_team.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}