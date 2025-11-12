<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "controle_technique")]
class ControleTechnique
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Vehicule::class, inversedBy: "controleTechniques")]
    #[ORM\JoinColumn(nullable: false)]
    private Vehicule $vehicule;

    #[ORM\ManyToOne(targetEntity: Professionnel::class)]
    private ?Professionnel $professionnel = null;

    #[ORM\ManyToOne(targetEntity: Garage::class)]
    private ?Garage $garage = null;

    #[ORM\Column(type: "date")]
    private \DateTime $dateControle;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('Initial', 'Contre-visite')", options: ["default" => "Initial"])]
    private string $typeControle;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('OK', 'Contre-visite', 'Echec')")]
    private string $resultat;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $observations = null;

    // --- getters et setters ---
    public function getId(): int { return $this->id; }
    public function getVehicule(): Vehicule { return $this->vehicule; }
    public function setVehicule(Vehicule $vehicule): self { $this->vehicule = $vehicule; return $this; }
    public function getProfessionnel(): ?Professionnel { return $this->professionnel; }
    public function setProfessionnel(?Professionnel $professionnel): self { $this->professionnel = $professionnel; return $this; }
    public function getGarage(): ?Garage { return $this->garage; }
    public function setGarage(?Garage $garage): self { $this->garage = $garage; return $this; }
    public function getDateControle(): \DateTime { return $this->dateControle; }
    public function setDateControle(\DateTime $dateControle): self { $this->dateControle = $dateControle; return $this; }
    public function getTypeControle(): string { return $this->typeControle; }
    public function setTypeControle(string $typeControle): self { $this->typeControle = $typeControle; return $this; }
    public function getResultat(): string { return $this->resultat; }
    public function setResultat(string $resultat): self { $this->resultat = $resultat; return $this; }
    public function getObservations(): ?string { return $this->observations; }
    public function setObservations(?string $observations): self { $this->observations = $observations; return $this; }
}
