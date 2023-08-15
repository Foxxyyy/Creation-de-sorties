<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class LostPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'label'=>'Adresse Mail',
                'mapped'=>false,
                'required'=>true,
                'attr'=>[
                    'placeholder'=>'Saisissez l\'adresse mail liée à votre compte'
                ]
            ])
            ->add('new_password', RepeatedType::class,[
                'type'=>PasswordType::class,
                'invalid_message'=>'Le mot de passe et la confirmation doivent etre identiques',
                'label'=>'Mon nouveau mot de passe',
                'mapped' => false,
                'first_options'=>['label'=>'Mon nouveau mot de passe'],
                'second_options'=>['label'=> 'Confirmez votre nouveau mot de passe'],
                'attr' => ['autocomplete' => 'new-password'],
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
        $builder->add('submit', SubmitType::class,[
            'label'=>'Enregistrer les modifications'
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
