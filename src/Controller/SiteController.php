<?php

    namespace App\Controller;

    use App\Entity\Site;
    use App\Form\SiteFormType;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route('/sites/', name: 'site_')]
    class SiteController extends AbstractController
    {
        #[IsGranted("ROLE_ADMIN")]
        #[Route(path:'index', name: 'index', methods: ['GET', 'POST'])]
        public function index(Request $request, EntityManagerInterface $entityManager) : Response
        {
            //appel à la méthode redéfinie dans le repository pour rechercher un site (getSitesWithSearch)
            if($site = $request->request->get('recherche') ?? '')
                $sites = $entityManager->getRepository(Site::class)->getSitesWithSearch($site);
            else // Récupération du site par l'identifiant passé à la route
                $sites = $entityManager->getRepository(Site::class)->findAll();

            // Passer le formulaire à la vue
            return $this->render('site/index.html.twig', [
                'sites' => $sites
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path:'create', name: 'create', methods: ['GET', 'POST'])]
        public function create(Request $request, EntityManagerInterface $entityManager) : Response
        {
            // Création de l'entité à remplir par l'utilisateur
            $site = new Site();
            $site -> setNom( $request->request->get('formSite')['nom'] ?? '');

            // Création de l'instance du formulaire
            $form = $this->createForm(SiteFormType::class, $site);
            $form->handleRequest($request);

            // Traitement du formulaire
            if ($form->isSubmitted() && $form->isValid())
            {
                // Insertion de l'entité dans la base de données
                $entityManager->persist($site);

                // Validation de la transaction
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash('success', 'Votre site a été ajouté avec succés !');
                return $this->redirectToRoute('site_index',);
            }

            // Passer le formulaire à la vue
            return $this->render('site/create.html.twig', [
                'formSite' => $form->createView(),
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path: '{id}/modifier', name: 'modifier', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
        public function modifier(int $id, EntityManagerInterface $entityManager, Request $request) : Response
        {
            // Récupération du site par l'identifiant passé à la route
            $site = $entityManager->getRepository(Site::class)->findOneBy(['id' => $id]);

            // Vérification de l'existance de l'objet
            if (is_null($site))
            {
                throw $this->createNotFoundException('Le site n\'existe pas !');
            }

            // Création de l'instance du formulaire
            $form = $this->createForm(SiteFormType::class, $site, ['mode' => 'modifier']);
            $form->handleRequest($request);

            // Traitement du formulaire
            if ($form->isSubmitted() && $form->isValid())
            {
                // Validation de la transaction
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash('success', 'Votre site a été modifiée avec succés !');

                // Redirection sur la page d'accueil'
                return $this->redirectToRoute('site_index' );
            }

            // Passer le formulaire à la vue
            return $this->render('site/details.html.twig', [
                'formSite' => $form->createView(),'site' => $site
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path: '{id}/remove', name: 'remove', requirements: ['id' => '\d+'], methods: ['GET'])]
        public function remove(int $id, EntityManagerInterface $entityManager) : Response
        {
            // Récupération du site par l'identifiant passé à la route
            $site = $entityManager->getRepository(Site::class)->findOneBy(['id' => $id]);

            // Vérification de l'existance de l'objet
            if (is_null($site))
            {
                throw $this->createNotFoundException('Le site n\'existe pas !');
            }

            // Suppression du site
            $entityManager->remove($site);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'Le site a été supprimé avec succès !');

            // Redirection sur la page d'accueil
            return $this->redirectToRoute('site_index');
        }
    }