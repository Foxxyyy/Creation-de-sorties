<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;

class ParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }


    /*public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Pseudo'),
            TextField::new('Nom'),
            TextField::new('Prenom'),
            TextField::new('Telephone'),
            EmailField::new('Mail'),
            //PasswordType::class('Mot de passe'),
            //AssociationField::new('Site')


        ];
    }
*/
}
