<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom', 'attr' => ['class' => '"mb-3 form-row text text-uppercase', 'style' => 'margin: 2% 0,']])
            ->add('prenom', TextType::class, ['label' => 'Prénom', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('email', EmailType::class, ['label' => 'E-mail', 'attr' => ['class' => '"mb-3 form-row', 'style' => 'margin: 2% 0,']])
            ->add('numTel', TelType::class, ['label' => 'Numéro de téléphone', 'attr' => ["pattern" => '^((\+\d{1,3}(-| )?\(?\d\)?(-| )?\d{1,5})|(\(?\d{2,6}\)?))(-| )?(\d{3,4})(-| )?(\d{4})(( x| ext)\d{1,5}){0,1}$', 'class' => '"mb-3 form-row']])
            ->add('mdp', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'required' => false,
                'attr' => ['class' => '"mb-3 form-row']])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Nouveau Mot de passe',
                'attr' => ['autocomplete' => 'new-password', 'class' => '"mb-3 form-row'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre nouveau Mot de passe devrait faire plus de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'attr' => ["style" => "margin-top: 1%;"]],
        );
    }
}
