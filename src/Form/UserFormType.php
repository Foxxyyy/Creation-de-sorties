<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class UserFormType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder->add('pseudo', TextType::class, [
            'disabled' => false,
            'data' => $this->security->getUser()->getPseudo(),
            'label' => 'Pseudo'
        ]);
        $builder->add('name', TextType::class,[
            'label'=> 'Prenom',
            'disabled' => true,
            'data' => $this->security->getUser()->getName(),
        ]);

        $builder->add('lastName', TextType::class,[
            'label'=>'Nom',
            'disabled' => true,
            'data' => $this->security->getUser()->getLastName(),
        ]);

        $builder->add('email', EmailType::class,[
            'label'=>'Email',
            'required'=>false,
            'data' => $this->security->getUser()->getEmail(),
            'attr'=>[
                'placeholder'=>'Saisissez votre email'
            ],
        ]);

        $builder->add('password', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'label'=>'Mot de passe',
            'data' => $this->security->getUser()->getPassword(),
            'disabled'=>true,
            //'data' => $this->security->getUser()->getPassword(),
            'constraints' => [
                new NotBlank([
                    'message' => 'Saisissez un mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ]);

        $builder->add('telephone', TelType::class,[
            'label'=>'Téléphone',
            'required'=>false,
            'attr'=>[
                'placeholder'=>'Saisissez votre numero de téléphone'
            ],
            'constraints' =>[
                new Length([
                    'min'=>10,
                    'minMessage'=> 'Votre numero de téléphone doit contenir au moins {{limit}} chiffres',
                    'max'=>20
                ])
            ]
        ]);

        $builder->add('site',EntityType::class, [
            'label'=>'Site',
            'class' => Site::class,
            'data' => $this->security->getUser()->getSite(),
            'choice_label' => 'nom',
            'mapped' => false,
            'disabled' => true,
        ]);

        $builder->add('photoFile', FileType::class, [
            'label' => 'Télécharger une photo',
            'required' => false,
            'mapped' => false,
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpeg, png, gif)',
                ]),
            ],
        ]);

        $builder->add('submit', SubmitType::class,[
            'label' => 'Modifier profil',
            'attr' => ['class' => 'btn btn-dark']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'pseudo'=>null,
            'name'=>null,
            'lastName'=>null,
            'trim' => true,
            'required' => true,
            'csrf_protection' => false,
        ]);
    }
}
