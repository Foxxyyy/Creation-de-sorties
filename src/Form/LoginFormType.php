<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('identifier', TextType::class, [
            'label' => 'Email ou Pseudo : ',
            'attr' => ['autocomplete' => 'off', 'placeholder' => 'Entrez votre email ou pseudo...'],
            'mapped' => false,
            'constraints' => [
                new NotBlank(['message' => 'L\'email ou le pseudo est requis !']),
            ],
        ]);


        $builder->add('password', PasswordType::class, [
            'label' => 'Mot de passe : ',
            'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Entrez votre mot de passe...'],
            'constraints' => [
                new NotBlank(['message' => 'Le mot de passe est requis !']),
            ],
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Se connecter',
            'attr' => ['class' => 'btn btn-dark']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'trim' => true,
            'required' => true,
            'enable_csrf' => true
        ]);
    }
}