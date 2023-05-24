<?php

namespace App\Entity;

use App\Repository\PlageHoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlageHoraireRepository::class)]
class PlageHoraire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("planning_api")]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups("planning_api")]
    private ?string $nomPlage = null;

    #[ORM\Column(type: "time")]
    #[Groups("planning_api")]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: "time")]
    #[Groups("planning_api")]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column]
    #[Groups("planning_api")]
    private ?int $numJour = null;

    #[ORM\ManyToOne(inversedBy: 'plagesHoraires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PlanningType $planningType = null;

    #[ORM\ManyToOne(inversedBy: 'plageHoraires', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("planning_api")]
    private ?Etat $etat = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPlage(): ?string
    {
        return $this->nomPlage;
    }

    public function setNomPlage(string $nomPlage): self
    {
        $this->nomPlage = $nomPlage;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getNumJour(): ?int
    {
        return $this->numJour;
    }

    public function setNumJour(int $numJour): self
    {
        $this->numJour = $numJour;

        return $this;
    }

    public function getPlanningType(): ?PlanningType
    {
        return $this->planningType;
    }

    public function setPlanningType(?PlanningType $planningType): self
    {
        $this->planningType = $planningType;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
