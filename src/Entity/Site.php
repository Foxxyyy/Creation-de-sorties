<?php

    namespace App\Entity;

    use App\Repository\SiteRepository;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\Table;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    #[ORM\Entity(repositoryClass: SiteRepository::class)]
    #[Table(name: 'Sites')]
    #[UniqueEntity(fields: ['nom'], message: 'Ce nom de site existe déjà ')]
    class Site
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(name:'id')]
        private ?int $id = null;

        #[NotBlank(message: 'Le nom est requis !')]
        #[Length(
            min: 5,
            max: 50,
            maxMessage: 'Le nom doit contenir au maximum {{ limit }} caractères !',
        )]
        #[Column(name: 'nom', type: 'string', length: 50)]
        private ?string $nom = null;

        #[ORM\OneToMany(mappedBy: 'site', targetEntity: Sortie::class)]
        private ?Collection $sorties = null;

        #[ORM\OneToMany(mappedBy: 'site', targetEntity: User::class)]
        private ?Collection $users = null;

        public function __construct() //Constructeur par défaut
        {

        }

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getNom(): ?string
        {
            return $this->nom;
        }

        public function setNom(string $nom): self
        {
            $this->nom = strtoupper($nom);

            return $this;
        }

        public function __toString(): string
        {
            return $this ->nom;
        }

    }