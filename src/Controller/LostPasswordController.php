<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LostPasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

        class LostPasswordController extends AbstractController
        {
            #[Route('/reset-password', name: 'app_reset_password')]
            //fonction permettant la reinitialisation du mot de passe
            public function resetPassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
            {

                $form = $this->createForm(LostPasswordFormType::class);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $email = $form->get('email')->getData();
                    $password = $form->get('new_password')->getData();
                    $user = $entityManager->getRepository(User::class)->findOneBy([
                        'email' => $email,
                    ]);

                    if ($user) {

                        // Modification du mot de passe
                        $user->setPassword($userPasswordHasher->hashPassword($user, $password));

                        $entityManager->flush();
                        // Message d'erreur
                        $this->addFlash('success', 'Le mot de passe à été modifié avec succès');

                        // Redirection vers une page de confirmation
                        return $this->redirectToRoute('app_login');

                    } else {

                        // Aucun utilisateur trouvé avec cette adresse email
                        $this->addFlash('error',"Adresse mail inconnue et/ou incorrecte");
                        return $this->redirectToRoute('app_reset_password');
                    }
                }

                return $this->render('lost_password/lost_password.html.twig', [
                    'lostPassword'=> $form->createView(),

                ]);
            }
        }
