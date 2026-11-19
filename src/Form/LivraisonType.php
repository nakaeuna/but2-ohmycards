<?php

namespace App\Form;

use App\Entity\EntiteAdresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroRue', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Numéro de rue'])
            ->add('nomRue', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Nom de rue'])
            ->add('ville', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Ville'])
            ->add('region', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Région'])
            ->add('codePost', NumberType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Code postal'])
            ->add('pays', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Pays'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EntiteAdresse::class,
        ]);
    }
}
