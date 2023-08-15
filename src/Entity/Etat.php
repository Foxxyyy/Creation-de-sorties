<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: EtatRepository::class)]
#[Table(name: 'Etats')]
class Etat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id')]
    private ?int $id = null;

    #[NotBlank(message: 'Le libellé est requis !')]
    #[Length(
        max: 30,
        maxMessage: 'Le libellé doit contenir au maximum {{ limit }} caractères !',
    )]
    #[ORM\Column(length: 30)]
    private ?string $libelle = null;

    public function __construct() //Constructeur par défaut
    {

    }

    public function getId() : ?int
    {
        return $this->id;
    }


    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
    }
    public function __toString()
    {
        return $this->libelle;
    }
}