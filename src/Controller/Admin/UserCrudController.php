<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{

//cette fonction est la fonction d'affichage par defaut
//elle se sert de l'entité pour generer les champs
    public static function getEntityFqcn(): string
    {
        return User::class;
    }
//configuration de l'affichage de la page Users
    public function configureCrud(Crud $crud): Crud{
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur');
    }
//cette fonction sert à afficher des champs personnalisés
//cette fonction par defaut est commentée
//
    public function configureFields(string $pageName): iterable
    {
        return [
            //le "TextField" genere l'equivalent d'un "input"
            TextField::new('pseudo')
                ->setRequired(true),
            TextField::new('name')
                ->setRequired(true),
            TextField::new('lastName')
                ->setRequired(true),
            //le Emailfield permet d'ajuster la saisie pour un email
            EmailField::new('email')
                ->setRequired(true),
            //pour le mot de passe il n'existe de PasswordField
            //donc on passe par un TextField
            TextField::new('password')
                //qu'on definit en la classe PasswordType
                ->setFormType(PasswordType::class)
                ->setRequired(true),
            TextField::new('telephone', 'Téléphone'),
            //Permet de créer un tableau dynamique pour l'insertion des roles

            //permet d'associer des entités entres elles
            AssociationField::new('site'),
            //crée des boutons dynamiques pour pourvoir modifier les propoétés associées
            BooleanField::new('actif', 'Actif'),
            BooleanField::new('administrateur', 'Admin'),
        ];
    }
}
