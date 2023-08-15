<?php

    namespace App\Entity;

    use App\Repository\VilleRepository;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\Table;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;
    use Symfony\Component\Validator\Constraints\NotNull;

    #[ORM\Entity(repositoryClass: VilleRepository::class)]
    #[Table(name: 'Villes')]
    class Ville
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

        #[NotNull(message: 'Le code postal est requis !')]
        #[Length(
            min: 5,
            max: 5,
            maxMessage: 'Le code postal doit contenir au maximum {{ limit }} caractères !',
        )]
        #[ORM\Column(name:'codePostal')]
        private ?int $codePostal = null;

        #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Lieu::class)]
        private ?Collection $lieux = null;

        public function __construct()
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
            $this->nom = ucfirst(strtolower($nom));
            return $this;
        }

        public function getCodePostal(): ?int
        {
            return $this->codePostal;
        }

        public function setCodePostal(int $codePostal): self
        {
            $this->codePostal = $codePostal;
            return $this;
        }

        public function getLieux(): ?Collection
        {
            return $this->lieux;
        }

        public function setLieux(?Collection $lieux): void
        {
            $this->lieux = $lieux;
        }
        public function addLieu(Lieu $lieu): self
        {
            if (!$this->lieux->contains($lieu)) {
                $this->lieux->add($lieu);
            }

            return $this;
        }
        public function __toString()
        {
            return $this->nom;
        }
    }