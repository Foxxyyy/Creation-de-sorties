<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
#[Table(name: 'Sorties')]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id')]
    private ?int $id = null;

    #[NotBlank(message: 'Le nom est requis !')]
    #[Length(
        max: 30,
        maxMessage: 'Le nom doit contenir au maximum {{ limit }} caractères !',
    )]
    #[ORM\Column(name: 'nom', length: 30)]
    private ?string $nom = null;

    #[ORM\Column(name: 'dateHeuredebut', type: 'datetime', nullable: false)]
    private ?DateTime $dateHeuredebut = null;

    #[ORM\Column(name: 'duree', nullable: true)]
    private ?int $duree = null;

    #[ORM\Column(name: 'dateLimiteInscription', type: 'datetime', nullable: false)]
    private ?DateTime $dateLimiteInscription = null;

    #[ORM\Column(name: 'nbInscriptionMax', nullable: false)]
    private ?int $nbInscriptionMax = null;

    #[Length(
        max: 500,
        maxMessage: 'La description doit contenir au maximum {{ limit }} caractères !',
    )]
    #[ORM\Column(name: 'infosSortie', length: 500, nullable: true)]
    private ?string $infosSortie = null;

    #[Length(
        max: 500,
        maxMessage: 'Le motif doit contenir au maximum {{ limit }} caractères !',
    )]
    #[ORM\Column(name: 'motifAnnulation', length: 500, nullable: true)]
    private ?string $motifAnnulation = null;

    #[NotBlank(message: 'Le site est requis !')]
    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'sorties')]
    #[JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Site $site = null;

    #[NotBlank(message: 'L\'état de sortie est requis !')]
    #[ORM\ManyToOne(targetEntity: Etat::class)]
    #[JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Etat $etatSortie = null;

    #[NotBlank(message: 'Le lieu est requis !')]
    #[ORM\ManyToOne(targetEntity: Lieu::class, inversedBy: 'lieux')]
    #[JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(targetEntity: Ville::class, inversedBy: 'villes')]
    #[JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Ville $ville = null;

    #[NotBlank(message: 'Le participant est requis !')]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'sorties')]
    #[JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'inscriptions')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getNom() : ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getDateHeuredebut() : ?DateTime
    {
        return $this->dateHeuredebut;
    }

    public function setDateHeuredebut(DateTime $dateHeuredebut): void
    {
        $this->dateHeuredebut = $dateHeuredebut;
    }

    public function getDuree() : ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function getDateLimiteInscription() : ?DateTime
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(DateTime $dateLimiteInscription): void
    {
        $this->dateLimiteInscription = $dateLimiteInscription;
    }

    public function getNbInscriptionMax() : ?int
    {
        return $this->nbInscriptionMax;
    }

    public function setNbInscriptionMax(int $nbInscriptionMax): void
    {
        $this->nbInscriptionMax = $nbInscriptionMax;
    }

    public function setInfosSortie(string $infosSortie): void
    {
        $this->infosSortie = $infosSortie;
    }

    public function getInfosSortie() : ?string
    {
        return $this->infosSortie;
    }

    public function getPhotoSortie(): ?string
    {
        return $this->photoSortie;
    }

    public function setPhotoSortie(?string $photoSortie): void
    {
        $this->photoSortie = $photoSortie;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): void
    {
        $this->ville = $ville;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): void
    {
        $this->site = $site;
    }

    public function getEtatSortie(): ?Etat
    {
        return $this->etatSortie;
    }

    public function setEtatSortie(?Etat $etatSortie): void
    {
        $this->etatSortie = $etatSortie;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): void
    {
        $this->lieu = $lieu;
    }

    /**
     * @return string|null
     */
    public function getMotifAnnulation(): ?string
    {
        return $this->motifAnnulation;
    }

    /**
     * @param string|null $motifAnnulation
     */
    public function setMotifAnnulation(?string $motifAnnulation): void
    {
        $this->motifAnnulation = $motifAnnulation;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user))
        {
            $this->users->add($user);
            $user->addInscription($this);
        }
        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user))
        {
            $user->removeInscription($this);
        }
        return $this;
    }
    public function __toString()
    {
        return $this->nom;
    }
}