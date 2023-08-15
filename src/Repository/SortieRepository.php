<?php

    namespace App\Repository;

    use App\Entity\Sortie;
    use DateInterval;
    use DateTime;
    use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
    use Doctrine\Persistence\ManagerRegistry;

    /**
     * @extends ServiceEntityRepository<Sortie>
     *
     * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
     * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
     * @method Sortie[]    findAll()
     * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     */
    class SortieRepository extends ServiceEntityRepository
    {
        public function __construct(ManagerRegistry $registry)
        {
            parent::__construct($registry, Sortie::class);
        }

        public function getSortiesAll()
        {
            $query = $this->createQueryBuilder('sorties')
                ->orderBy('sorties.dateHeuredebut');

            return $query->getQuery()->getResult();
        }

        public function getSorties(String $site, String $recherche, String $date1, String $date2,
                                   String $idOrganisateur,String $idInscrit, String $idNonInscrit, String $idEtatSortiePassees)
        {
            //Calcul de dates pour ne pas afficher les sorties passées depuis 1 mois
            $dateActuelle = new DateTime('now');
            $interval = new DateInterval('PT1M');
            $datePlusUnMois = $dateActuelle-> add($interval);

            //Création de la requete en filtrant les sorties passées depuis plus d'un mois
            $query = $this->createQueryBuilder('sorties')
                ->innerJoin('sorties.site','site')->addSelect('site')
                //->andWhere('sorties.dateHeuredebut > :datePlusUnmois')
                //->setParameter('datePlusUnmois',$datePlusUnMois)
            ;

            //Requête si le site en renseigné
            if($site != '')
            {
                $query->andwhere('sorties.site = :idSite')->setParameter('idSite',$site);
            }
            //Requete avec le champ de recherche remplie
            if($recherche != '')
            {
                $query->andwhere('sorties.nom LIKE :valeur')
                    ->andWhere('sorties.site = :idSite')
                    ->setParameter('valeur','%'.$recherche.'%')
                    ->setParameter('idSite',$site);
            }
            //requete avec l'intervalle de dates
            if($date1 != '')
            {
                $query->andwhere('sorties.dateHeuredebut >= :date1')->setParameter('date1',$date1)
                    ->andWhere('sorties.site = :idSite')->setParameter('idSite',$site);
            }
            if($date2 != '')
            {
                $query->andwhere('sorties.dateHeuredebut <= :date2')->setParameter('date2',$date2)
                    ->andWhere('sorties.site = :idSite')->setParameter('idSite',$site);
            }
            //Si la case "sortie dont je suis l'organisateur" est cochée
            if($idOrganisateur != '')
            {
                $query->andWhere('sorties.user = :idOrga')
                    ->setParameter('idOrga',$idOrganisateur);
            }
            //Si la case "sortie auxquelles je suis inscrit" est cochée
            if($idInscrit != '')
            {
                $query->andWhere(':idInscrit MEMBER OF sorties.users')
                    ->setParameter('idInscrit', $idInscrit);
            }
            //Si la case "sortie auxquelles je ne suis pas inscrit" est cochée
            if($idNonInscrit != '')
            {
                $query->andWhere(':idNonInscrit NOT MEMBER OF sorties.users')
                    ->setParameter('idNonInscrit', $idNonInscrit);
            }

            //Si la case "sortie passées" est cochée
            if($idEtatSortiePassees != '')
            {
                $query->andwhere('sorties.etatSortie = :idEtat')
                    ->setParameter('idEtat', $idEtatSortiePassees);
            }
            $query->orderBy('sorties.dateHeuredebut');

            return $query->getQuery()->getResult();
        }
    }