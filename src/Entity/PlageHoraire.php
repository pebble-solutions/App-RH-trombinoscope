<?php

namespace App\Entity;

use App\Repository\PlageHoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
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

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups("planning_api")]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups("planning_api")]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column]
    #[Groups("planning_api")]
    private ?int $numJour = null;


    #[ORM\ManyToOne(inversedBy: 'PlageHoraire')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PlanningType $planningType = null;

    #[ORM\ManyToOne(inversedBy: 'plageHoraires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $Etats = null;

    public function __construct()
    {
        $this->planningTypes = new ArrayCollection();
        $this->etats = new ArrayCollection();
    }


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

//
//    public function addPlanningType(PlanningFormType $planningType): self
//    {
//        if (!$this->planningTypes->contains($planningType)) {
//            $this->planningTypes->add($planningType);
//            $planningType->addPlagesHoraire($this);
//        }
//
//        return $this;
//    }
//
//    public function removePlanningType(PlanningFormType $planningType): self
//    {
//        if ($this->planningTypes->removeElement($planningType)) {
//            $planningType->removePlagesHoraire($this);
//        }
//
//        return $this;
//    }


    public function getPlanningType(): ?PlanningType
    {
        return $this->planningType;
    }

    public function setPlanningType(?PlanningType $planningType): self
    {
        $this->planningType = $planningType;

        return $this;
    }

    public function getEtats(): ?Etat
    {
        return $this->Etats;
    }

    public function setEtats(?Etat $Etats): self
    {
        $this->Etats = $Etats;

        return $this;
    }



}
