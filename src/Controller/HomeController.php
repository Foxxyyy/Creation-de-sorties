<?php

    namespace App\Controller;

    use App\Entity\Etat;
    use App\Entity\Site;
    use App\Entity\Sortie;
    use DateTime;
    use Doctrine\ORM\EntityManagerInterface;
    use mysqli;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        /**
         * @param EntityManagerInterface $entityManager
         * @param Request $request
         * @return Response
         * page d'accueil une fois connecté
         * l'utilisateur peut afficher les sorties en fonction des filtres
         */
        #[Route('/home', name: 'app_home', methods: ['GET', 'POST'])]
        public function home(EntityManagerInterface $entityManager, Request $request): Response
        {
            //Récupération des données de l'utilisateur connecté
            $user = $this->getUser();
            //Vérification que l'utilisateur est bien passé par la page de connexion, sinon redirection vers la page de login
            if ($user == null) {
                return $this->redirectToRoute('app_login');
            }
            else {
                //récupération des éléments du formulaire HTML:
                $site = $request->request->get('site') ?? '';
                $recherche = $request->request->get('recherche') ?? '';
                $date1 = $request->request->get('date1') ?? '';
                $date2 = $request->request->get('date2') ?? '';
                $idOrganisateur = $request->request->get('checkboxOrga') ?? '';
                $idInscrit = $request->request->get('checkboxInscrit') ?? '';
                $idNonInscrit = $request->request->get('checkboxNonInscrit') ?? '';
                $sortiesPassees = $request->request->get('checkboxPassees') ?? '';

                //Appel au repository pour gérer l'ensemble des filtres liées à la sortie
                $sorties = $entityManager->getRepository(Sortie::class)->getSorties($site, $recherche, $date1, $date2, $idOrganisateur, $idInscrit, $idNonInscrit, $sortiesPassees);

                //Vérification des états des sorties et modification automatique
                $mysqli = new mysqli('localhost', 'root', '', 'sortirajms');
                $mysqli->query('CALL majEtat4EnCours');
                $mysqli->query('CALL majEtat3PourDateClotureInscriptionAtteinte');
                $mysqli->query('CALL majEtat5Terminee');

                //Récupération de la liste des sites
                $sites = $entityManager->getRepository(Site::class)->findAll();

                //Récupération des états de la sortie
                $etats = $entityManager->getRepository(Etat::class)->findAll();

                //Récupération du site de rattachement du user
                $siteRecherche = null;
                if ($site != '')
                    $siteRecherche = $entityManager->getRepository(Site::class)->findOneBy(['id' => $site]);

                return $this->render('home/index.html.twig', [
                    'sorties' => $sorties,
                    'user' => $user,
                    'sites' => $sites,
                    'site_recherche' => $siteRecherche,
                    'etats' => $etats,
                ]);
            }
        }
    }