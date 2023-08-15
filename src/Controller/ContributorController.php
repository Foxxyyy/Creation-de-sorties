<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Form\UserFormType;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\File\UploadedFile;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route('/profile/', name: 'profile_')]
    class ContributorController extends AbstractController
    {
        #[IsGranted("ROLE_INACTIF")]
        #[Route(path: 'edit', name: 'edit')]
        public function edit(Request $request, EntityManagerInterface $entityManager) : Response
        {
            $user = $this->getUser();
            $form = $this->createForm(UserFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                $user->setPseudo($form->get('pseudo')->getData());
                $user->setFirstName($form->get('name')->getData());
                $user->setLastName($form->get('lastName')->getData());
                $user->setEmail($form->get('email')->getData());
                $user->setTelephone($form->get('telephone')->getData());
                $user->setSite($form->get('site')->getData());
                //$user->setRoles(array('ROLE_ADMIN')); //A décommenter si vous voulez ajouter un rôle admin lors de la modif d'un profil

                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $form->get('photoFile')->getData();

                if ($uploadedFile)
                {
                    $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                    $uploadedFile->move($destination, $newFilename);
                    $user->setPhoto($newFilename);
                }
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Votre profil a bien été modifié !');
                return $this->redirectToRoute('app_home');
            }

            return $this->render('profile/edit.html.twig',[
                'contributorForm' => $form->createView(),
            ]);
        }


        #[IsGranted("ROLE_INACTIF")]
        #[Route(path: 'view_{id}', name: 'view', requirements: ['id' => '\d+'])]
        public function view(User $profile) : Response
        {
            $form = $this->createForm(UserFormType::class, $profile, ['disabled' => true]);
            $form->remove('password');
            $form->remove('photoFile');
            $form->remove('submit');

            return $this->render('profile/view.html.twig',[
                'contributorForm' => $form->createView(),
                'profile' => $profile
            ]);
        }
    }