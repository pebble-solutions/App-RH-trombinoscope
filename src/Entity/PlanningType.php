<?php

namespace App\Entity;

use App\Repository\PlanningTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PlanningTypeRepository::class)]
class PlanningType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("planning_api")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("planning_api")]
    private ?int $idEmploye = null;

    #[ORM\Column(length: 255)]
    #[Groups("planning_api")]
    private ?string $inom = null;

    #[ORM\OneToMany(mappedBy: 'planningType', targetEntity: PlageHoraire::class, orphanRemoval: true)]
    private Collection $plagesHoraires;

    public function __construct()
    {
        $this->plagesHoraires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdEmploye(): ?int
    {
        return $this->idEmploye;
    }

    public function setIdEmploye(int $idEmploye): self
    {
        $this->idEmploye = $idEmploye;

        return $this;
    }

    public function getInom(): ?string
    {
        return $this->inom;
    }

    public function setInom(string $inom): self
    {
        $this->inom = $inom;

        return $this;
    }

    /**
     * @return Collection<int, PlageHoraire>
     */
    public function getPlagesHoraires(): Collection
    {
        return $this->plagesHoraires;
    }

    public function addPlageHoraire(PlageHoraire $plageHoraire): self
    {
        if (!$this->plagesHoraires->contains($plageHoraire)) {
            $this->plagesHoraires[] = $plageHoraire;
            $plageHoraire->setPlanningType($this);
        }

        return $this;
    }

    public function removePlageHoraire(PlageHoraire $plageHoraire): self
    {
        if ($this->plagesHoraires->contains($plageHoraire)) {
            $this->plagesHoraires->removeElement($plageHoraire);
            // set the owning side to null (unless already changed)
            if ($plageHoraire->getPlanningType() === $this) {
                $plageHoraire->setPlanningType(null);
            }
        }

        return $this;
    }
}


//<?php
//
//namespace App\Entity;
//
//use App\Repository\PlanningTypeRepository;
//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;
//use Doctrine\ORM\Mapping as ORM;
//use Symfony\Component\Serializer\Annotation\Groups;
//
//#[ORM\Entity(repositoryClass: PlanningTypeRepository::class)]
//class PlanningFormType
//{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column]
//    #[Groups("planning_api")]
//    private ?int $id = null;
//
//    #[ORM\Column]
//    #[Groups("planning_api")]
//    private ?int $idEmploye = null;
//
//
//    #[ORM\Column(length: 255)]
//    #[Groups("planning_api")]
//    private ?string $inom = null;
//
//    #[ORM\OneToMany(mappedBy: 'planningType', targetEntity: PlageHoraire::class, orphanRemoval: true)]
//    private Collection $PlagesHoraires;
//
//    public function __construct()
//    {
//        $this->plagesHoraires = new ArrayCollection();
//        //$this->PlageHoraire = new ArrayCollection();
//    }
//
//    public function getId(): ?int
//    {
//        return $this->id;
//    }
//
//    public function getIdEmploye(): ?int
//    {
//        return $this->idEmploye;
//    }
//
//    public function setIdEmploye(int $idEmploye): self
//    {
//        $this->idEmploye = $idEmploye;
//
//        return $this;
//    }
//
//
//    public function getInom(): ?string
//    {
//        return $this->inom;
//    }
//
//    public function setInom(string $inom): self
//    {
//        $this->inom = $inom;
//
//        return $this;
//    }
//
//    /**
//     * @return Collection<int, PlageHoraire>
//     */
//    public function getPlagesHoraires(): Collection
//    {
//        return $this->PlagesHoraires;
//    }
//
//    public function addPlagesHoraire(PlageHoraire $plagesHoraire): self
//    {
//        if (!$this->PlagesHoraires->contains($plagesHoraire)) {
//            $this->PlagesHoraires->add($plagesHoraire);
//            $plagesHoraire->setPlanningType($this);
//        }
//
//        return $this;
//    }
//
//    public function removePlagesHoraire(PlageHoraire $plagesHoraire): self
//    {
//        if ($this->PlagesHoraires->removeElement($plagesHoraire)) {
//            // set the owning side to null (unless already changed)
//            if ($plagesHoraire->getPlanningType() === $this) {
//                $plagesHoraire->setPlanningType(null);
//            }
//        }
//
//        return $this;
//    }
//
//
//}
