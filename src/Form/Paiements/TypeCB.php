<?php

namespace App\Form\Paiements;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeCB extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cb', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Numéro de carte : '])
            ->add('exp', DateType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Date d\'expiration : '])
            ->add('securityCode', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Code de sécurité : '])
            ->add('name', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Nom sur la carte : '])
            //todo verify CB
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
