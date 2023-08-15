<?php

    namespace App\Form;

    use App\Entity\Ville;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class VilleFormType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options) : void
        {
            $builder->add('nom', TextType::class, [
                'label' => 'Nom de la ville : *',
                'attr' => ['autocomplete' => 'off', 'placeholder' => 'Saisissez le nom d\'une ville']
            ]);

            $builder->add('codePostal',IntegerType::class, [
                'label' => 'Code postal de la ville : *',
                'attr' => ['min' => 10000, 'max' => 99999, 'placeholder' => 'Saisissez un code postal']
            ]);

            $builder->add('submit', SubmitType::class, [
                'label' => ($options['mode'] === 'add' ? 'Ajouter' : 'Modifier') . ' la ville',
                'attr' => ['class' => 'btn btn-dark']
            ]);
        }

        public function configureOptions(OptionsResolver $resolver) : void
        {
            $resolver->setDefaults([
                'data_class' => Ville::class,
                'mode' => 'add',
                'required' => true
            ]);
        }
    }