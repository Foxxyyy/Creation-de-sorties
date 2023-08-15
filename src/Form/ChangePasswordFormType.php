<?php

    namespace App\Form;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    class ChangePasswordFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options) : void
        {
            $builder
                ->add('old_password',PasswordType::class,[
                    'label' => 'Mot de passe actuel',
                    'mapped' => false,
                    'attr' => [
                        'placeholder' => 'Veuillez saisir votre mot de passe actuel'
                    ]
                ])
                ->add('new_password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                    'label' => 'Mon nouveau mot de passe',
                    'mapped' => false,
                    'first_options' => [
                        'label' => 'Mon nouveau mot de passe',
                        'attr' => [
                            'placeholder' => 'Veuillez saisir un nouveau mot de passe'
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Confirmez votre nouveau mot de passe',
                        'attr' => [
                            'placeholder' => 'Veuillez confirmer le nouveau mot de passe'
                        ]
                    ],
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Saisissez un mot de passe'
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                            'max' => 4096
                        ]),
                    ],
                ])
            ;
            $builder->add('submit', SubmitType::class, [
                'label'=>'Enregistrer les modifications',
                'attr' => ['class' => 'btn btn-dark']
            ]);
        }

        public function configureOptions(OptionsResolver $resolver) : void
        {
            $resolver->setDefaults([
                'data_class' => User::class
            ]);
        }
    }