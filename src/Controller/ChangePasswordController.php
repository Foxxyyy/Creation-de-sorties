<?php

    namespace App\Controller;

    use App\Form\ChangePasswordFormType;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

    class ChangePasswordController extends AbstractController
    {
        #[Route('/change-password', name: 'app_change_password')]
        public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, EntityManagerInterface $entityManager): Response
        {

            //recuperation de l'utilisateur connecté
            $user = $this->getUser();
            //creation du formulaire
            $form = $this->createForm(ChangePasswordFormType::class, $user);
            //traitement de la requete http pour un formulaire
            $form->handleRequest($request);

            //a la validation du formulaire
            if ($form->isSubmitted() && $form->isValid()) {
                //recuperation de la donnée ancien MDP
                $old_pwd = $form->get('old_password')->getData();

                //verification du MDP
                if ($userPasswordHasher->isPasswordValid($user, $old_pwd)) {
                    //recuperation du nouveau MDP
                    $form->get('new_password')->getData();
                    //hash du MDP
                    $user->setPassword(
                        $userPasswordHasher->hashPassword($user, $form->get('new_password')->getData())
                    );
                    //insertion en base à la place de l'ancien MDP
                    $entityManager->flush();
                    //affichage des message de reussite ou echec et redirection eventuelles
                    $this->addFlash('success', "Votre mot de passe à bien été mis à jour.") ;
                    return $this->redirectToRoute('app_home');

                } else {
                    $this->addFlash('error', "Votre mot de passe n'est pas le bon.");
                }
            }
            //affichage du formualire
            return $this->render('change-password/password.html.twig', [
                'changePasswordForm'=> $form->createView(),

            ]);
        }
    }
