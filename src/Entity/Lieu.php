<?php

    namespace App\Entity;

    use App\Repository\LieuRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\JoinColumn;
    use Doctrine\ORM\Mapping\Table;
    use Symfony\Component\Validator\Constraints\Length;
    use Symfony\Component\Validator\Constraints\NotBlank;

    #[ORM\Entity(repositoryClass: LieuRepository::class)]
    #[Table(name: 'Lieux')]
    class Lieu
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column(name: 'id', type: 'integer')]
        private ?int $id = null;

        #[NotBlank(message: 'Le nom est requis !')]
        #[Length(
            max: 50,
            maxMessage: 'Le nom doit contenir au maximum {{ limit }} caractères !',
        )]

        #[Column(name: 'nom', type: 'string', length: 50)]
        private ?string $nom = null;

        #[Length(
            max: 50,
            maxMessage: 'La rue doit contenir au maximum {{ limit }} caractères !',
        )]
        #[Column(name: 'rue', type: 'string', length: 50, nullable: true)]
        private ?string $rue = null;

        #[Column(name: 'latitude', type: 'float', nullable: true)]
        private ?string $latitude = null;

        #[Column(name: 'longitude', type: 'float', nullable: true)]
        private ?string $longitude = null;

        #[NotBlank(message: 'la ville est requise !')]
        #[ORM\ManyToOne(targetEntity: Ville::class, inversedBy: 'villes')]
        #[JoinColumn(referencedColumnName: 'id', nullable: false)]
        private ?Ville $ville = null;

        public function __construct() //Constructeur par défaut
        {

        }

        public function getId() : ?int
        {
            return $this->id;
        }

        public function getNom(): ?string
        {
            return $this->nom;
        }

        public function setNom(?string $nom): void
        {
            $this->nom = $nom;
        }

        public function getRue(): ?string
        {
            return $this->rue;
        }

        public function setRue(?string $rue): void
        {
            $this->rue = $rue;
        }

        public function getLatitude(): ?string
        {
            return $this->latitude;
        }

        public function setLatitude(?string $latitude): void
        {
            $this->latitude = $latitude;
        }

        public function getLongitude(): ?string
        {
            return $this->longitude;
        }

        public function setLongitude(?string $longitude): void
        {
            $this->longitude = $longitude;
        }

        public function getVille(): ?Ville
        {
            return $this->ville;
        }

        public function setVille(?Ville $ville): void
        {
            $this->ville = $ville;
        }
        public function __toString()
        {
            return $this->nom;
        }

        public function setLieu(?Lieu $lieu): void
        {
            $this->lieu = $lieu;
        }

    }
