<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Ville;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom',
            'attr' => ['autocomplete' => 'off']
        ]);

        $builder->add('ville', EntityType::class, [
            'label' => 'Ville',
            'placeholder' => 'Veuillez choisir une ville',
            'attr' => ['class' => 'form-control'],
            'class' => Ville::class,

            'choice_label' => 'nom',
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' =>  'Valider',
        ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
