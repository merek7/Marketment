<?php

namespace App\Entity;

use App\Repository\ProspectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=ProspectRepository::class)
 * @UniqueEntity(fields={"entreprises"}, message="Une rapport a deja ete enregistrer pour cette entreprisse")
 */
class Prospect
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * 
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="prospects")
     * @ORM\JoinColumn(nullable=false, unique=true)
     * 
     */
    private $entreprises;

    /**
     * @ORM\OneToOne(targetEntity=Rdv::class, mappedBy="prospects", cascade={"persist", "remove"})
     */
    private $rdvs;


    public function __toString()
    {
        return ($this->entreprises->getNom());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getEntreprises(): ?Entreprise
    {
        return $this->entreprises;
    }

    public function setEntreprises(?Entreprise $entreprises): self
    {
        $this->entreprises = $entreprises;

        return $this;
    }

    public function getRdvs(): ?Rdv
    {
        return $this->rdvs;
    }

    public function setRdvs(Rdv $rdvs): self
    {
        // set the owning side of the relation if necessary
        if ($rdvs->getProspects() !== $this) {
            $rdvs->setProspects($this);
        }

        $this->rdvs = $rdvs;

        return $this;
    }
}
