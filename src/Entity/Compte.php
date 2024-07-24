<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="float")
     */
    private $solde;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="comptes", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false,unique=false)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $statut;

    /**
     * @ORM\ManyToOne(targetEntity=TypeCompte::class, inversedBy="comptes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=HistoriqueT::class, mappedBy="compte")
     */
    private $historiqueTs;

    /**
     * @ORM\Column(type="string", length=3, unique=true)
     */
    private $rib;

    public function __construct()
    {
        $this->historiqueTs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $Statut): self
    {
        $this->statut = $Statut;

        return $this;
    }

    public function getType(): ?TypeCompte
    {
        return $this->type;
    }

    public function setType(?TypeCompte $Type): self
    {
        $this->type = $Type;

        return $this;
    }

    /**
     * @return Collection<int, HistoriqueT>
     */
    public function getHistoriqueTs(): Collection
    {
        return $this->historiqueTs;
    }

    public function addHistoriqueT(HistoriqueT $historiqueT): self
    {
        if (!$this->historiqueTs->contains($historiqueT)) {
            $this->historiqueTs[] = $historiqueT;
            $historiqueT->setCompte($this);
        }

        return $this;
    }

    public function removeHistoriqueT(HistoriqueT $historiqueT): self
    {
        if ($this->historiqueTs->removeElement($historiqueT)) {
            // set the owning side to null (unless already changed)
            if ($historiqueT->getCompte() === $this) {
                $historiqueT->setCompte(null);
            }
        }

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }
}
