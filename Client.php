namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "client")]
class Client
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 100)]
    private string $nom;

    #[ORM\Column(type: "string", length: 100)]
    private string $prenom;

    #[ORM\Column(type: "string", length: 150, unique: true, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(type: "datetime", options: ["default" => "CURRENT_TIMESTAMP"])]
    private \DateTime $dateInscription;

    #[ORM\OneToMany(mappedBy: "client", targetEntity: Vehicule::class)]
    private Collection $vehicules;

    #[ORM\OneToMany(mappedBy: "client", targetEntity: RendezVous::class)]
    private Collection $rendezVous;

    public function __construct() {
        $this->vehicules = new ArrayCollection();
        $this->rendezVous = new ArrayCollection();
        $this->dateInscription = new \DateTime();
    }

    // --- getters et setters ---
    public function getId(): int { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function setNom(string $nom): self { $this->nom = $nom; return $this; }
    public function getPrenom(): string { return $this->prenom; }
    public function setPrenom(string $prenom): self { $this->prenom = $prenom; return $this; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): self { $this->email = $email; return $this; }
    public function getTelephone(): ?string { return $this->telephone; }
    public function setTelephone(?string $telephone): self { $this->telephone = $telephone; return $this; }
    public function getAdresse(): ?string { return $this->adresse; }
    public function setAdresse(?string $adresse): self { $this->adresse = $adresse; return $this; }
    public function getDateInscription(): \DateTime { return $this->dateInscription; }
    public function getVehicules(): Collection { return $this->vehicules; }
    public function getRendezVous(): Collection { return $this->rendezVous; }
}