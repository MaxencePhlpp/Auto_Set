<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "garage_professionnel")]
class GarageProfessionnel
{
    #[ORM\Id, ORM\ManyToOne(targetEntity: Garage::class, inversedBy: "garageProfessionnels")]
    #[ORM\JoinColumn(nullable: false)]
    private Garage $garage;

    #[ORM\Id, ORM\ManyToOne(targetEntity: Professionnel::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Professionnel $professionnel;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $role = null;

    // --- getters et setters ---
    public function getGarage(): Garage { return $this->garage; }
    public function setGarage(Garage $garage): self { $this->garage = $garage; return $this; }
    public function getProfessionnel(): Professionnel { return $this->professionnel; }
    public function setProfessionnel(Professionnel $professionnel): self { $this->professionnel = $professionnel; return $this; }
    public function getRole(): ?string { return $this->role; }
    public function setRole(?string $role): self { $this->role = $role; return $this; }
}