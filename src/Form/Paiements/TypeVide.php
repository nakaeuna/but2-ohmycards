<?php

namespace App\Form\Paiements;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeVide extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paypal', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Nom d\'utilisateur PayPal : '])
            ->add('mdp', TextType::class, ['attr' => ["class" => 'mb-3 form-row'], 'label' => 'Mot de passe PayPal : '])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
