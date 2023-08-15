<?php

namespace App\Form;

use App\Entity\Site;
use App\Entity\Sortie;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheSortiesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('site',EntityType::class,[
                'label'=>'site : ',
                'class'=> Site::class,
                'choice_label' => 'nom',
            ]);
        $builder
            ->add('nom', SearchType::class,[
                'label'=>'Le nom de la sortie contient',
                'required'=>false,
            ]);
        $builder
            ->add('dateHeuredebut',DateType::class,[
                    'label'=>'Entre '
                ]
            );
        $builder
            ->add('dateHeuredebut',DateType::class,[
                'label'=>'et '
            ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Rechercher',
        ]);

        $builder->add('etatSortie',TextType::class,[
            'required'=>false,
        ]);

            /*->add('nbInscriptionMax')
            ->add('infosSortie')
            ->add('motifAnnulation')
            ->add('photoSortie')
            ->add('duree')

            ->add('lieu')
            ->add('user')
            ->add('users')
            */

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
