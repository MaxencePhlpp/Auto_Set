<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "rendez_vous")]
class RendezVous
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: "rendezVous")]
    #[ORM\JoinColumn(nullable: false)]
    private Client $client;

    #[ORM\ManyToOne(targetEntity: Vehicule::class, inversedBy: "rendezVous")]
    #[ORM\JoinColumn(nullable: false)]
    private Vehicule $vehicule;

    #[ORM\ManyToOne(targetEntity: Professionnel::class)]
    private ?Professionnel $professionnel = null;

    #[ORM\ManyToOne(targetEntity: Garage::class)]
    private ?Garage $garage = null;

    #[ORM\Column(type: "datetime")]
    private \DateTime $dateRdv;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('Controle technique', 'Contre-visite')")]
    private string $typeRdv;

    #[ORM\Column(type: "string", columnDefinition: "ENUM('Prévu','Effectué','Annulé')", options: ["default" => "Prévu"])]
    private string $statut;

    // --- getters et setters ---
    public function getId(): int { return $this->id; }
    public function getClient(): Client { return $this->client; }
    public function setClient(Client $client): self { $this->client = $client; return $this; }
    public function getVehicule(): Vehicule { return $this->vehicule; }
    public function setVehicule(Vehicule $vehicule): self { $this->vehicule = $vehicule; return $this; }
    public function getProfessionnel(): ?Professionnel { return $this->professionnel; }
    public function setProfessionnel(?Professionnel $professionnel): self { $this->professionnel = $professionnel; return $this; }
    public function getGarage(): ?Garage { return $this->garage; }
    public function setGarage(?Garage $garage): self { $this->garage = $garage; return $this; }
    public function getDateRdv(): \DateTime { return $this->dateRdv; }
    public function setDateRdv(\DateTime $dateRdv): self { $this->dateRdv = $dateRdv; return $this; }
    public function getTypeRdv(): string { return $this->typeRdv; }
    public function setTypeRdv(string $typeRdv): self { $this->typeRdv = $typeRdv; return $this; }
    public function getStatut(): string { return $this->statut; }
    public function setStatut(string $statut): self { $this->statut = $statut; return $this; }
}
