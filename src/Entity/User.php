<?php

    namespace App\Entity;

    use App\Repository\UserRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\ORM\Mapping as ORM;
    use Doctrine\ORM\Mapping\Column;
    use Doctrine\ORM\Mapping\JoinColumn;
    use Doctrine\ORM\Mapping\ManyToOne;
    use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
    use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
    use Symfony\Component\Security\Core\User\UserInterface;
    use Symfony\Component\Validator\Constraints\Length;

    #[ORM\Entity(repositoryClass: UserRepository::class)]
    #[ORM\Table(name: 'Users')]
    #[UniqueEntity(fields: ['email'], message: 'Compte déjà existant avec cette adresse mail')]
    #[UniqueEntity(fields: ['pseudo'], message: 'Pseudo déjà existant ')]
    class User implements UserInterface, PasswordAuthenticatedUserInterface
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $name = null;

        #[ORM\Column(length: 255)]
        private ?string $email = null;

        #[ORM\Column(length: 255)]
        private ?string $password = null;

        #[ORM\Column(length: 255)]
        private ?string $lastName = null;

        #[Column(name: 'pseudo', type: 'string', length: 50)]
        private ?string $pseudo = null;

        #[Column(name: 'telephone', type: 'string', length: 20, nullable: true)]
        private ?string $telephone = null;

        #[ORM\Column(name:'administrateur')]
        private ?bool $administrateur = false;

        #[ORM\Column(name: 'role')]
        private array $roles = [];

        #[ORM\Column(name:'actif')]
        private ?bool $actif = false;

        #[Length(
            max: 250,
            maxMessage: 'L\'URL doit contenir au maximum {{ limit }} caractères !',
        )]
        #[ORM\Column(name: 'photo', length: 250, nullable: true)]
        private ?string $photo = null;

        #[ManyToOne(targetEntity: Site::class, inversedBy: 'site')]
        #[JoinColumn(referencedColumnName: 'id')]
        private ?Site $site = null;

        #[ORM\OneToMany(mappedBy: 'sortie', targetEntity: Sortie::class)]
        private Collection $sorties;

        #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'inscriptions')]
        private Collection $inscriptions;

        public function __construct()
        {
            $this->inscriptions = new ArrayCollection();
        }
        public function getId(): ?int
        {
            return $this->id;
        }

        public function getFirstName(): ?string
        {
            return $this->name;
        }

        public function setFirstName(string $name): self
        {
            $this->name = $name;

            return $this;
        }

        public function getLastName(): ?string
        {
            return $this->lastName;
        }

        public function setLastName(string $lastName): self
        {
            $this->lastName = $lastName;

            return $this;
        }

        public function getEmail(): ?string
        {
            return $this->email;
        }

        public function setEmail(string $email): self
        {
            $this->email = $email;

            return $this;
        }

        public function getPassword(): ?string
        {
            return $this->password;
        }

        public function setPassword(string $password): self
        {
            $this->password = $password;

            return $this;
        }

        public function getUsername(): ?string
        {
            return $this->email;
        }

        /**
         * @return string|null
         */
        public function getName(): ?string
        {
            return $this->name;
        }

        /**
         * @param string|null $name
         */
        public function setName(?string $name): void
        {
            $this->name = $name;
        }

            public function getRoles(): array
            {
                $roles = $this->roles;

                if (in_array('ROLE_ADMIN',$roles))
                    $roles[] = 'ROLE_USER';
                if (in_array('ROLE_USER',$roles) || in_array('ROLE_ADMIN',$roles))
                    $roles[] = 'ROLE_INACTIF';

                return array_unique($roles);
            }

            public function setRoles(array $roles): void
            {
                $this->roles = $roles;
            }

        public function getSalt(): ?string
        {
            // not needed when using "bcrypt" for password hashing
            return null;
        }

        public function eraseCredentials()
        {
            // if you had any temporary, sensitive data stored on the user, clear it here
            // $this->plainPassword = null;
        }

        public function getUserIdentifier(): string
        {
            return $this->getEmail();
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

        public function getAdministrateur(): ?bool
        {
            return $this->administrateur;
        }

        public function setAdministrateur(?bool $administrateur): void
        {
            $this->administrateur = $administrateur;
        }

        public function getActif(): ?bool
        {
            return $this->actif;
        }

        public function setActif(?bool $actif): void
        {
            $this->actif = $actif;
        }

        public function getPhoto(): ?string
        {
            return $this->photo;
        }

        public function setPhoto(string $photo): void
        {
            $this->photo = $photo;
        }

        public function getSite(): ?Site
        {
            return $this->site;
        }

        public function setSite(Site $site): void
        {
            $this->site = $site;
        }

        public function getInscriptions(): Collection
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
            $this->inscriptions->removeElement($inscription);return $this;
        }
    }