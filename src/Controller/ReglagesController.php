<?php

namespace App\Controller;

use App\Entity\EntiteAdresse;
use App\Entity\Utilisateur;
use App\Form\AdresseType;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReglagesController extends AbstractController
{
    #[Route('/reglages', name: 'app_reglages_2')]
    public function reglages(
        Request $request,
        Security $security,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var Utilisateur $user */
        $user = $security->getUser();
        $commandes = $user->getCommandes();
        $adresse = $user->getAdresse();
        if ($adresse == null) {
            $adresse = new EntiteAdresse();
        }

        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        $form2 = $this->createForm(AdresseType::class, $adresse);
        $form2->handleRequest($request);

        if ($form2->isSubmitted() && $form2->isValid()) {
            $entityManager->persist($adresse);
            $entityManager->flush();
            return $this->render('reglages/reglages.html.twig', ['form' => $form->createView(), 'form2' => $form2->createView(), 'commandes' => $commandes]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $mdp = $form->get('mdp')->getData();

            if (!empty($mdp) || !empty($plainPassword)) {
                if (empty($mdp)) {
                    $form->addError(new FormError("Mot de passe actuel non-renseigné."));
                }
                if (empty($plainPassword)) {
                    $form->addError(new FormError("Nouveau mot de passe non-renseigné."));
                }
                if (!$userPasswordHasher->isPasswordValid($user, $mdp)) {
                    $form->addError(new FormError("Mot de passe actuel incorrect."));
                }
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            }

            $user->setNom($form->get('nom')->getData());
            $user->setPrenom($form->get('prenom')->getData());
            $user->setEmail($form->get('email')->getData());
            $user->setNumTel($form->get('numTel')->getData());

            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('reglages/reglages.html.twig', ['form' => $form->createView(), 'form2' => $form2->createView(), 'commandes' => $commandes]);
    }
}
