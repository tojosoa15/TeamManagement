<?php

namespace App\Form;

use App\Entity\Team;
use App\Form\PlayerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'équipe',
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays de l\'équipe',
            ])
            ->add('silverSale', TextType::class, [
                'label' => 'Vente d\'argent',
            ])
            ->add('players', CollectionType::class, [
                'label'         => ' ',
                'entry_type'    => PlayerType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add'     => true,
                'allow_delete'  => true,
                'delete_empty'  => true,
                'prototype'     => true,
                'by_reference'  => false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Ajouter',
                'attr'  => ['class' => 'btn btn-primary'],
            ])
            ->add('return_list', ButtonType::class, [
                'label' => 'Retour à la liste',
                'attr'  => ['class' => 'btn btn-secondary', 'id' => 'app_sell_buy']
            ])
            ->add('sale_player_by', ButtonType::class, [
                'label' => 'Vendre/acheter',
                'attr'  => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}