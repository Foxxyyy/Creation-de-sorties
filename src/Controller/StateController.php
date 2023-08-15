<?php

    namespace App\Controller;

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class StateController extends AbstractController
    {
        #[IsGranted("ROLE_USER")]
        #[Route('/state', name: 'app_state')]
        public function state(): Response
        {
            return $this->render('state/edit.html.twig');
        }
    }