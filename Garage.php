<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "garage")]
class Garage
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: "string", length: 150, unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\OneToMany(mappedBy: "garage", targetEntity: RendezVous::class)]
    private Collection $rendezVous;

    #[ORM\OneToMany(mappedBy: "garage", targetEntity: ControleTechnique::class)]
    private Collection $controleTechniques;

    #[ORM\OneToMany(mappedBy: "garage", targetEntity: GarageProfessionnel::class)]
    private Collection $garageProfessionnels;

    public function __construct() {
        $this->rendezVous = new ArrayCollection();
        $this->controleTechniques = new ArrayCollection();
        $this->garageProfessionnels = new ArrayCollection();
    }

    // --- getters et setters ---
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): self { $this->adresse = $adresse; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): self { $this->telephone = $telephone; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): self { $this->email = $email; return $this; }
    public function getRendezVous(): Collection { return $this->rendezVous; }
    public function getControleTechniques(): Collection { return $this->controleTechniques; }
    public function getGarageProfessionnels(): Collection { return $this->garageProfessionnels; }
}
