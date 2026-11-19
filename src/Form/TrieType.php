<?php

namespace App\Form;

use App\Controller\Recherche\RechercheData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\TypeInfo\Type\EnumType;

class TrieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', SearchType::class, ['label' => false, 'attr' => ['placeholder' => '🔎 Recherche', 'class' => 'search-bar'], 'required' => false])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Tous' => categorie::produit,
                    'Jeux de cartes' => categorie::jeuxDeCarte,
                    'Trading Card Game' => categorie::TCG,
                    'Jeux de société' => categorie::jeuxDeSociete,
                ],
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => fn() => ['class' => 'btn-check'],
                'attr' => ['class' => 'btn-group-vertical'], // Add a class to the container
            ])
            ->add('theme', ChoiceType::class, [
                'choices' => [
                    'Tous' => theme::TOUS,
                    'Yu-Gi-Oh!' => theme::YU_GI_OH,
                    'Pokémon' => theme::POKEMON,
                    'Classique' => theme::CLASSIQUE,
                    'Hello Kitty' => theme::HELLO_KITTY
                ], 'label' => false, 'data' => theme::TOUS,
                'expanded' => true,
                'multiple' => false,
                'choice_attr' => fn() => ['class' => 'btn-check'],
                'attr' => ['class' => 'btn-group-vertical'],
            ])

            ->add('prixMin', NumberType::class, ['html5' => true, 'required' => false, 'label' => false, 'data' => 0,
                'attr' => array(
                    'class' => 'form-control me-2',
                    // Ajouter 'data' => find min
                    'min' => 0, //todo find min
                    'max' => 9999, //todo find max
                    'step' => 1,
                    // Lier le prix min avec le prix max
                )])
            ->add('prixMax', NumberType::class, ['html5' => true, 'required' => false, 'label' => false, 'data' => 9999,
                'attr' => array(
                    'class' => 'form-control',
                    // Ajouter 'data' => find max
                    'min' => 0, //todo find min
                    'max' => 9999, //todo find max
                    'step' => 1,
                )])
            ->setAttribute('id', 'search_form');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RechercheData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
