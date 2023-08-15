<?php

    namespace App\Controller;

    use App\Entity\Ville;
    use App\Form\VilleFormType;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route('/villes/', name: 'city_')]
    class CityController extends AbstractController
    {
        #[IsGranted("ROLE_ADMIN")]
        #[Route(path:'index', name: 'index', methods: ['GET', 'POST'])]
        public function index(Request $request, EntityManagerInterface $entityManager) : Response
        {
            //appel à la méthode redéfinie dans le repository pour rechercher une ville (getVillesWithSearch)
            if($ville = $request->request->get('recherche')??'')
                $villes = $entityManager->getRepository(Ville::class)->getVillesWithSearch($ville);
            else
              // Récupération de la ville par l'identifiant passé à la route
             $villes = $entityManager->getRepository(Ville::class)->findAll();

            // Passer le formulaire à la vue
            return $this->render('city/index.html.twig', [
                'villes' => $villes
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path: '/', name: 'city', methods:['GET','POST'])]
        public function city(EntityManagerInterface $entityManager, Request $request) : Response
        {
            //Récupération des éléments du formulaire HTML:
            $recherche =  $request->request->get('recherche')?? '';

            //Récupération des villes en fonction du champ de recherche
            if($recherche !== '')
            {
                $villes = $entityManager->getRepository(Ville::class)->f($recherche);
            }

            //récupération de la liste des villes
            $villes = $entityManager->getRepository(Ville::class)->findAll();

            return $this->render('city/index.html.twig',[
                'villes' => $villes,
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path:'create', name: 'create', methods: ['GET', 'POST'])]
        public function create(Request $request, EntityManagerInterface $entityManager) : Response
        {
            // Création de l'entité à remplir par l'utilisateur
            $ville = new Ville();
            $ville -> setNom( $request->request->get('formVille')['nom'] ?? '');

            // Création de l'instance du formulaire
            $form = $this->createForm(VilleFormType::class, $ville);
            $form->handleRequest($request);

            // Traitement du formulaire
            if ($form->isSubmitted() && $form->isValid())
            {
                // Insertion de l'entité dans la base de données
                $entityManager->persist($ville);

                // Validation de la transaction
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash('success', 'Votre ville a été ajouté avec succés !');
                return $this->redirectToRoute('city_index',);

            }

             // Passer le formulaire à la vue
            return $this->render('city/create.html.twig', [
                'formVille' => $form->createView(),
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path: '{id}/modifier', name: 'modifier', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
        public function modifier(int $id, EntityManagerInterface $entityManager, Request $request) : Response
        {
            // Récupération de la ville par l'identifiant passé à la route
            $ville = $entityManager->getRepository(Ville::class)->findOneBy(['id' => $id]);

            // Vérification de l'existance de l'objet
            if (is_null($ville))
            {
                throw $this->createNotFoundException('Le ville n\'existe pas !');
            }

            // Création de l'instance du formulaire
            $form = $this->createForm(VilleFormType::class, $ville, ['mode' => 'modifier']);
            $form->handleRequest($request);

            // Traitement du formulaire
            if ($form->isSubmitted() && $form->isValid())
            {
                // Validation de la transaction
                $entityManager->flush();

                // Message de confirmation
                $this->addFlash('success', 'Votre ville a été modifiée avec succés !');

                // Redirection sur la page d'accueil
                return $this->redirectToRoute('city_index', [
                    'id' => $ville->getId(),
                ]);
            }

            // Passer le formulaire à la vue
            return $this->render('city/details.html.twig', [
                'formVille' => $form->createView(),'ville' => $ville
            ]);
        }

        #[IsGranted("ROLE_ADMIN")]
        #[Route(path: '{id}/remove', name: 'remove', requirements: ['id' => '\d+'], methods: ['GET'])]
        public function remove(int $id, EntityManagerInterface $entityManager) : Response
        {
            // Récupération de la ville par l'identifiant passé à la route
            $ville = $entityManager->getRepository(Ville::class)->findOneBy(['id' => $id]);

            // Vérification de l'existance de l'objet
            if (is_null($ville))
            {
                throw $this->createNotFoundException('La ville n\'existe pas !');
            }

            // Suppression de la ville
            $entityManager->remove($ville);

            // Validation de la transaction
            $entityManager->flush();

            // Message de confirmation
            $this->addFlash('success', 'La ville a été supprimé avec succès !');

            // Redirection sur la page d'accueil
            return $this->redirectToRoute('city_index');
        }
    }