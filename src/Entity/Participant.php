<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[ORM\Table(name: 'Participants')]
class Participant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column (name: 'id', type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[NotBlank(message: 'Le pseudo est requis !')]
    #[Length(
        max: 50,
        maxMessage: 'Le pseudo doit contenir au maximum {{ limit }} caractères !',
    )]
    #[Column(name: 'pseudo', type: 'string', length: 50)]
    private ?string $pseudo = null;

    #[NotBlank(message: 'Le nom est requis !')]
    #[Length(
        max: 50,
        maxMessage: 'Le nom doit contenir au maximum {{ limit }} caractères !',
    )]
    #[Column(name: 'nom', type: 'string', length: 50)]
    private ?string $nom = null;


    #[NotBlank(message: 'Le prénom est requis !')]
    #[Length(
        max: 50,
        maxMessage: 'Le prénom doit contenir au maximum {{ limit }} caractères !',
    )]
    #[Column(name: 'prenom', type: 'string', length: 50)]
    private ?string $prenom = null;

   /* #[NotBlank(message: 'Le telephone est requis !')]*/
    #[Length(
        max: 20,
        maxMessage: 'Le telephone doit contenir au maximum {{ limit }} caractères !',
    )]
    #[Column(name: 'telephone', type: 'string', length: 20)]
    private ?string $telephone = null;

    #[Email(message: 'L\'email est invalide !')]
    #[ORM\Column(name:'mail', length: 100, unique: true)]
    private ?string $mail = null;

    #[ORM\Column(name: 'password', length: 100)]
    private ?string $password = null;

    #[ORM\Column(name:'administrateur')]
    private ?bool $administrateur = false;

    #[ORM\Column(name:'actif')]
    private ?bool $actif = false;

    #[NotBlank(message: 'Le site est requis !')]
    #[ManyToOne(targetEntity: Site::class, inversedBy: 'sites')]
    #[JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?Site $site = null;

    #[ORM\OneToMany(mappedBy: 'sortie', targetEntity: Sortie::class)]
    private Collection $sorties;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'participants')]
    private \Doctrine\Common\Collections\Collection $inscriptions;

    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @return string|null
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * @param string|null $pseudo
     */
    public function setPseudo(?string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string|null
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @param string|null $mail
     */
    public function setMail(?string $mail): void
    {
        $this->mail = $mail;
    }

    /**
     * @return bool|null
     */
    public function getAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    /**
     * @param bool|null $administrateur
     */
    public function setAdministrateur(?bool $administrateur): void
    {
        $this->administrateur = $administrateur;
    }

    /**
     * @return bool|null
     */
    public function getActif(): ?bool
    {
        return $this->actif;
    }

    /**
     * @param bool|null $actif
     */
    public function setActif(?bool $actif): void
    {
        $this->actif = $actif;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection<int, Sortie>
     */
    public function getInscriptions(): \Doctrine\Common\Collections\Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Sortie $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions->add($inscription);
        }

        return $this;
    }

    public function removeInscription(Sortie $inscription): self
    {
        $this->inscriptions->removeElement($inscription);

        return $this;
    }
    public function __toString()
    {
        return $this->pseudo;
    }
}
