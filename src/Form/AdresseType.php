<?php

namespace App\Form;

use App\Entity\EntiteAdresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codePost', NumberType::class, ['label' => 'Code Postal', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('nomRue', TextType::class, ['label' => 'Nom de rue', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('numeroRue', TextType::class, ['label' => 'Numéro de rue', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('region', TextType::class, ['label' => 'Région', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('pays', TextType::class, ['label' => 'Pays', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('ville', TextType::class, ['label' => 'Ville', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntiteAdresse::class,
        ]);
    }
}
