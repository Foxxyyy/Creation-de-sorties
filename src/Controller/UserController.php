<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Form\UserFormType;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class UserController extends AbstractController
    {
        #[IsGranted("ROLE_USER")]
        #[Route('/modifier-profil', name: 'app_user')]
        public function register(Request $request) : Response
        {
            $user = new User();
            $form = $this->createForm(UserFormType::class, $user, [
                'action' => $this->generateUrl('app_user', ['id' => $id]),
                'method' => 'POST',
                'enctype' => 'multipart/form-data', // Ajout de cet attribut
            ]);

            $form->handleRequest($request);
            if ($form->isSubmitted() and $form->isValid())
            {
                dd($form);
            }

            return $this->render('user/edit.html.twig', [
                'userForm' => $form->createView(),
            ]);
        }
    }