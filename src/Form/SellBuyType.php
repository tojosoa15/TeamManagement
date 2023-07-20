<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class SellBuyType extends AbstractType
{
    /**
    * @param EntityManagerInterface $entityManager
    */
    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('selling_team', ChoiceType::class, [
                'label'     => 'Équipe vendeuse',
                'choices'   => $this->getTeamsChoices(),
            ])
            ->add('buying_team', ChoiceType::class, [
                'label'     => 'Équipe acheteuse',
                'choices'   => $this->getTeamsChoices(),
            ])
            ->add('player', ChoiceType::class, [
                'label'     => 'Joueur',
                'choices'   => $this->getPlayersChoices(),
            ])
            ->add('amount', MoneyType::class, [
                'label' => 'Montant',
            ])
            ->add('sell_buy', SubmitType::class, [
                'label' => 'Valider',
            ])
            ->add('add_players', ButtonType::class, [
                'label' => 'Ajouter une équipe',
                'attr'  => ['class' => 'btn btn-secondary'],
            ])
            ->add('return_list', ButtonType::class, [
                'label' => 'Retour à la liste',
                'attr'  => ['class' => 'btn btn-secondary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    private function getTeamsChoices()
    {
        // Récupérer toutes les équipes depuis la base de données
        $teams = $this->entityManager->getRepository(Team::class)->findAll();

        // Préparer les choix pour le champ ChoiceType
        $choices = [];
        foreach ($teams as $team) {
            $choices[$team->getName()] = $team;
        }

        return $choices;
    }

    private function getPlayersChoices()
    {
        // Récupérer tous les joueurs depuis la base de données
        $players = $this->entityManager->getRepository(Player::class)->findAll();

        // Préparer les choix pour le champ ChoiceType
        $choices = [];
        foreach ($players as $player) {
            $choices[$player->getName()] = $player;
        }

        return $choices;
    }
}