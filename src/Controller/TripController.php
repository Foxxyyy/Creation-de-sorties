<?php

    namespace App\Controller;

    use App\Entity\Etat;
    use App\Entity\Lieu;
    use App\Entity\Site;
    use App\Entity\Sortie;
    use App\Entity\User;
    use App\Entity\Ville;
    use App\Form\LieuFormType;
    use App\Form\SortieFormType;
    use DateTime;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Component\Security\Core\Security;

    #[Route('/trip/', name: 'trip_')]
    class TripController extends AbstractController
    {
        #[IsGranted("ROLE_USER")]
        #[Route(path: '', name: 'trip')]
        #[Route(path: 'create', name: 'create')]
        public function create(Request $request, EntityManagerInterface $entityManager, Security $security) : Response
        {
            $user = $security->getUser();
            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['id' => 1]);

            $sortie = new Sortie();
            $sortie->setUser($user);
            $sortie->setEtatSortie($etat);
            $sortie->setSite($user->getSite());
            $sortie->setLieu($request->request->get('tripForm')['lieu'] ?? null);

            $form = $this->createForm(SortieFormType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                if ($form->get('publish')->isClicked())
                {
                    $etat = $entityManager->getRepository(Etat::class)->findOneBy(['libelle' => 'Ouverte']);
                    $sortie->setEtatSortie($etat);
                }
                $sortie->setVille($sortie->getLieu()->getVille());

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a bien été créée !');
                return $this->redirectToRoute('app_home');
            }

            return $this->render('trip/create-trip.html.twig', [
                'tripForm' => $form->createView()
            ]);
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'edit_{id}', name: 'edit', requirements: ['id' => '\d+'])]
        public function edit(Sortie $sortie, Request $request, EntityManagerInterface $entityManager) : Response
        {
            $lieu = $entityManager->getRepository(Lieu::class)->findOneBy(['nom' => $sortie->getLieu()->getNom()]);
            $ville = $entityManager->getRepository(Ville::class)->findOneBy(['id' => $lieu->getVille()->getId()]);

            if ($ville != null)
            {
                $lieu->setVille($ville);
            }

            $sortie->setLieu($lieu);
            $form = $this->createForm(SortieFormType::class, $sortie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                if ($form->get('publish')->isClicked())
                {
                    $etat = $entityManager->getRepository(Etat::class)->findOneBy(['id' => 2]);
                    $sortie->setEtatSortie($etat);
                }

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a bien été modifiée !');

                return $this->redirectToRoute('app_home');
            }

            return $this->render('trip/edit.html.twig', [
                'edit' => true,
                'sortie' => $sortie,
                'editTripForm' => $form->createView()
            ]);
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'view_{id}', name: 'view', requirements: ['id' => '\d+'])]
        public function view(Sortie $sortie) : Response
        {
            $form = $this->createForm(SortieFormType::class, $sortie, ['disabled' => true]);
            $form->remove('save');
            $form->remove('publish');

            return $this->render('trip/edit.html.twig', [
                'edit' => false,
                'sortie' => $sortie,
                'viewTripForm' => $form->createView()
            ]);
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'delete_{id}', name: 'delete', requirements: ['id' => '\d+'])]
        public function delete(Sortie $sortie, EntityManagerInterface $entityManager) : Response
        {
            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a bien été supprimée !');
            return $this->redirectToRoute('app_home');
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'register_{id}', name: 'register', requirements: ['id' => '\d+'])]
        public function register(Sortie $sortie, Request $request, EntityManagerInterface $entityManager, Security $security) : Response
        {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $security->getUser()->getId()]);
            $sortie->addUser($user);

            //Si le nombre d'inscrits est égal au nombre de places, l'état passe à 'clôturé'
            if (sizeof($sortie->getUsers()) == $sortie->getNbInscriptionMax() || new DateTime('now') > $sortie->getDateLimiteInscription())
            {
                $etat = $entityManager->getRepository(Etat::class)->findOneBy(['id' => 3]);
                $sortie->setEtatSortie($etat);
            }

            $this->addFlash('success', 'Vous avez bien été inscrit(e) à la sortie !');
            $entityManager->persist($sortie);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'desist_{id}', name: 'desist', requirements: ['id' => '\d+'])]
        public function desist(Sortie $sortie, EntityManagerInterface $entityManager, Security $security) : Response
        {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $security->getUser()->getId()]);
            $sortie->removeUser($user);

            //Le participant se désiste, il y a donc une place en plus, l'état repasse à 'ouvert' s'il était 'clôturé'
            if ($sortie->getEtatSortie()->getId() == 3 && sizeof($sortie->getUsers()) < $sortie->getNbInscriptionMax() && new DateTime('now') < $sortie->getDateLimiteInscription())
            {
                $etat = $entityManager->getRepository(Etat::class)->findOneBy(['id' => 2]);
                $sortie->setEtatSortie($etat);
            }

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez bien été retiré(e) de la sortie !');
            return $this->redirectToRoute('app_home');
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'publish_{id}', name: 'publish', requirements: ['id' => '\d+'])]
        public function publish(Sortie $sortie, EntityManagerInterface $entityManager) : Response
        {
            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['id' => 2]);
            $sortie->setEtatSortie($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a bien été publiée !');
            return $this->redirectToRoute('app_home');
        }

        #[IsGranted("ROLE_USER")]
        #[Route(path: 'cancel_{id}', name: 'cancel', requirements: ['id' => '\d+'])]
        public function cancel(Sortie $sortie, EntityManagerInterface $entityManager) : Response
        {
            $etat = $entityManager->getRepository(Etat::class)->findOneBy(['id' => 6]);
            $sortie->setEtatSortie($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a bien été annulée !');
            return $this->redirectToRoute('app_home');
        }
        #[IsGranted("ROLE_USER")]
        #[Route(path: 'addLieu_', name: 'addLieu')]
        public function addLieu( Request $request ,EntityManagerInterface $entityManager) : Response
        {
            // Création de l'entité à remplir par l'utilisateur
            $lieu = new Lieu();

            // Création de l'instance du formulaire
            $form = $this->createForm(LieuFormType::class, $lieu);
            $form->handleRequest($request);


                 // Traitement du formulaire
                 if ($form->isSubmitted() && $form->isValid()) {

                // Insertion de l'entité dans la base de données
                $entityManager->persist($lieu);

                // Validation de la transaction
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash('success', 'Votre lieu a été ajouté avec succés !');


                // Redirection sur la page d'accueil'
                return $this->redirectToRoute('trip_trip', [
                    'id' => $lieu->getId(),
                ]);
                 }

            // Passer le formulaire à la vue
            return $this->render('trip/trip_addLieu.html.twig', [
                'formLieu' => $form->createView(),
            ]);
        }


    }