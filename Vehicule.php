<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "vehicule")]
class Vehicule
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "vehicules")]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $marque = null;

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private ?string $modele = null;

    #[ORM\Column(type: "string", length: 20, unique: true, nullable: true)]
    private ?string $immatriculation = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $kilometrage = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $anneeSortie = null;

    #[ORM\Column(type: "date", nullable: true)]
    private ?\DateTime $dateDernierControle = null;

    #[ORM\OneToMany(mappedBy: "vehicule", targetEntity: RendezVous::class)]
    private Collection $rendezVous;

    #[ORM\OneToMany(mappedBy: "vehicule", targetEntity: ControleTechnique::class)]
    private Collection $controleTechniques;

    public function __construct() {
        $this->rendezVous = new ArrayCollection();
        $this->controleTechniques = new ArrayCollection();
    }

    // --- getters et setters ---
    public function getId(): int { return $this->id; }
    public function getClient(): Client { return $this->client; }
    public function setClient(Client $client): self { $this->client = $client; return $this; }
    public function getCategorie(): ?string { return $this->categorie; }
    public function setCategorie(?string $categorie): self { $this->categorie = $categorie; return $this; }
    public function getMarque(): ?string { return $this->marque; }
    public function setMarque(?string $marque): self { $this->marque = $marque; return $this; }
    public function getModele(): ?string { return $this->modele; }
    public function setModele(?string $modele): self { $this->modele = $modele; return $this; }
    public function getImmatriculation(): ?string { return $this->immatriculation; }
    public function setImmatriculation(?string $immatriculation): self { $this->immatriculation = $immatriculation; return $this; }
    public function getKilometrage(): ?int { return $this->kilometrage; }
    public function setKilometrage(?int $kilometrage): self { $this->kilometrage = $kilometrage; return $this; }
    public function getAnneeSortie(): ?int { return $this->anneeSortie; }
    public function setAnneeSortie(?int $anneeSortie): self { $this->anneeSortie = $anneeSortie; return $this; }
    public function getDateDernierControle(): ?\DateTime { return $this->dateDernierControle; }
    public function setDateDernierControle(?\DateTime $dateDernierControle): self { $this->dateDernierControle = $dateDernierControle; return $this; }
    public function getRendezVous(): Collection { return $this->rendezVous; }
    public function getControleTechniques(): Collection { return $this->controleTechniques; }
}