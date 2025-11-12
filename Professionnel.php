<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "professionnel")]
class Professionnel
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenom;

    #[ORM\Column(type: "string", length: 150, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $motDePasse;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('Secretariat','Mécanicien','Directeur','Stagiaire')")]
    private string $emploi;

    #[ORM\OneToMany(mappedBy: "professionnel", targetEntity: RendezVous::class)]
    private Collection $rendezVous;

    #[ORM\OneToMany(mappedBy: "professionnel", targetEntity: ControleTechnique::class)]
    private Collection $controleTechniques;

    public function __construct() {
        $this->rendezVous = new ArrayCollection();
        $this->controleTechniques = new ArrayCollection();
    }

    // --- getters et setters ---
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }
    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getMotDePasse(): string { return $this->motDePasse; }
    public function setMotDePasse(string $motDePasse): self { $this->motDePasse = $motDePasse; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): self { $this->telephone = $telephone; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): self { $this->adresse = $adresse; return $this; }
    public function getEmploi(): string { return $this->emploi; }
    public function setEmploi(string $emploi): self { $this->emploi = $emploi; return $this; }
    public function getRendezVous(): Collection { return $this->rendezVous; }
    public function getControleTechniques(): Collection { return $this->controleTechniques; }
}
