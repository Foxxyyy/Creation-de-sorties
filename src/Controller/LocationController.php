<?php

    namespace App\Controller;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class LocationController extends AbstractController
    {
        #[IsGranted("ROLE_USER")]
        #[Route('/location', name: 'app_location')]
        public function location() : Response
        {
            return $this->render('location/edit.html.twig', [
                'controller_name' => 'LocationController',
            ]);
        }
    }
