<?php

    namespace App\Controller;

    use App\Repository\LieuRepository;
    use App\Repository\VilleRepository;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;

    #[Route(path: "/ajax", name: "ajax_")]
    class AjaxController extends AbstractController
    {
        #[IsGranted("ROLE_USER")]
        #[Route(path: "/rechercheLieuByVille", name: "rechercher_lieu_by_ville")]
        public function rechercheLieuByVille(Request $request , LieuRepository  $lieuRepository)
        {
            //declaration des variables
            $json_data = array();
            $i = 0;

            //recherche les lieux correspondant à la ville selectionnée
            $lieux = $lieuRepository->findBy(['ville' => $request->request->get('ville_id')]);

            //si lieux trouvées ...
            if (sizeof($lieux) > 0)
            {
                //pour chaque lieu, hydratation d'un tableau
                foreach ($lieux as $lieu)
                {
                    $json_data[$i++] = array('id' => $lieu->getId(), 'nom' => $lieu->getNom());
                }
                //renvoie un tableau au format json
                return new JsonResponse($json_data);
            }
            else
            {
                //hydratation du tableau avec : Pas de lieu correspondant à cette ville.
                $json_data[$i++] = array('id' => '', 'nom' => 'Pas de lieu correspondant à cette ville.');
                //renvoie un tableau au format json
                return new JsonResponse($json_data);
            }
        }
    }