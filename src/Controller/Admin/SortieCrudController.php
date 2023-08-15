<?php

    namespace App\Controller\Admin;

    use App\Entity\Sortie;
    use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
    use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
    use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

    class SortieCrudController extends AbstractCrudController
    {
        public static function getEntityFqcn(): string
        {
            return Sortie::class;
        }


        public function configureFields(string $pageName): iterable
        {
            return [

                TextField::new('nom', 'Nom')->setRequired(true),
                DateTimeField::new('dateHeuredebut', 'Date et heure de début'),
                DateTimeField::new('dateLimiteInscription', "Date limite d'inscription"),
                NumberField::new('duree', 'Durée'),
                NumberField::new('nbInscriptionMax', "Nombre d'inscription maximum"),
                AssociationField::new('ville'),
                AssociationField::new('lieu'),
                TextareaField::new('infosSortie', 'Description et infos'),

            ];
        }

    }