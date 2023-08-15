<?php

    namespace App\Form;

    use App\Entity\Lieu;
    use App\Entity\Sortie;
    use App\Entity\Ville;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Form\FormEvent;
    use Symfony\Component\Form\FormEvents;
    use Symfony\Component\Form\FormInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class SortieFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options) : void
        {
            $builder->add('nom', TextType::class, [
                'label' => 'Nom de la sortie : *',
                'attr' => ['placeholder' => 'Saisissez le nom de la sortie'],
            ]);

            $builder->add('dateHeuredebut', DateTimeType::class, [
                'label' => 'Date et heure de la sortie : *',
                'html5' => true,
                'widget' => 'single_text'
            ]);

            $builder->add('dateLimiteInscription', DateTimeType::class, [
                'label' => 'Date limite d\'inscription : *',
                'html5' => true,
                'widget' => 'single_text',
            ]);

            $builder->add('nbInscriptionMax', IntegerType::class, [
                'label' => 'Nombre de places : *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisissez le nombre de places pour la sortie',
                    'min' => 1,
                ],
            ]);

            $builder->add('duree', IntegerType::class, [
                'label' => 'Durée (en minutes) :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisissez la durée de la sortie',
                    'min' => 1,
                ]
            ]);

            $builder->add('ville', EntityType::class, [
                'label' => 'Ville : *',
                'class' => Ville::class,
                'choice_label' => 'nom',
                'mapped' => false,
                'placeholder' => '-- Choisissez une ville --',
                'attr' => ['class' => 'villeSelect']
            ]);

            /* Evenement declenché après avoir rempli l'entité */
            $builder->get('ville')->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event)
            {
                    $form = $event->getForm();
                    $this->addLieuField($form->getParent(), $form->getData());
                }
            );

            /* Evenement déclenché juste avant la fin du chargement des données dans le formulaire */
            $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event)
            {
                    $data = $event->getData();
                    /* @var $lieu \App\Entity\Lieu */
                    $lieu = $data->getLieu();
                    $form = $event->getForm();

                    if ($lieu)
                    {
                        $ville = $lieu->getVille();
                        $this->addLieuField($form, $ville);
                        $form->get('ville')->setData($ville);
                    }
                    else
                    {
                        $this->addLieuField($form, null);
                    }

                }
            );

            $builder->add('infosSortie', TextType::class, [
                'label' => 'Description et infos :',
                'attr' => ['placeholder' => 'Saisissez une description'],
                'required' => false
            ]);

            $builder->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-dark']
            ]);

            $builder->add('publish', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => ['class' => 'btn btn-dark']
            ]);

        }

        //ajout du select lieu au formulaire
        private function addLieuField(FormInterface $form, ?Ville $ville)
        {
            $builder = $form->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'label'=> 'Lieu : *',
                'choice_label' => 'nom',
                'placeholder' => '-- Choisissez un lieu --',
                'required' => true,
                'auto_initialize' => false,
                'choices' => $ville ? $ville->getLieux() : []
            ]);
        }

        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Sortie::class,
            ]);
        }
    }