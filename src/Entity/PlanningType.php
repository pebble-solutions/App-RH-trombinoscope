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


    #[ORM\ManyToMany(targetEntity: PlageHoraire::class, inversedBy: 'planningTypes')]
    private Collection $plagesHoraires;

    #[ORM\Column(length: 255)]
    #[Groups("planning_api")]
    private ?string $inom = null;

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

    /**
     * @return Collection<int, PlageHoraire>
     */
    public function getPlagesHoraires(): Collection
    {
        return $this->plagesHoraires;
    }

    public function addPlagesHoraire(PlageHoraire $plagesHoraire): self
    {
        if (!$this->plagesHoraires->contains($plagesHoraire)) {
            $this->plagesHoraires->add($plagesHoraire);
        }

        return $this;
    }

    public function removePlagesHoraire(PlageHoraire $plagesHoraire): self
    {
        $this->plagesHoraires->removeElement($plagesHoraire);

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


}
