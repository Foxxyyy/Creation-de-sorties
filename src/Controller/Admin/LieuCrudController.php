<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Nom')->setRequired(true),
            TextField::new('Rue')->setRequired(true),
            AssociationField::new('ville'),
            TextField::new('Latitude'),
            TextField::new('Longitude'),

        ];
    }

}
