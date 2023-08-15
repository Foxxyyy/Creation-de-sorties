<?php

namespace App\Form;

use App\Entity\Site;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('nom', TextType::class, [
            'label' => 'Nom du site : *',
            'attr' => ['autocomplete' => 'off', 'placeholder' => 'Saisissez le nom d\'un site']
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => ($options['mode'] === 'add' ? 'Ajouter' : 'Modifier') . ' le site',
            'attr' => ['class' => 'btn btn-dark']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Site::class,
            'mode' => 'add',
        ]);
    }
}
