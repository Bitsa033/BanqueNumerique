<?php

namespace App\Entity;

use App\Repository\HistoriqueTRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueTRepository::class)
 */
class HistoriqueT
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateT;

    /**
     * @ORM\Column(type="bigint")
     */
    private $solde;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numero_compte;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateT(): ?\DateTimeInterface
    {
        return $this->dateT;
    }

    public function setDateT(\DateTimeInterface $dateT): self
    {
        $this->dateT = $dateT;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(string $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getNumeroCompte(): ?string
    {
        return $this->numero_compte;
    }

    public function setNumeroCompte(string $numero_compte): self
    {
        $this->numero_compte = $numero_compte;

        return $this;
    }

    public function getTitulaire(): ?string
    {
        return $this->titulaire;
    }

    public function setTitulaire(string $titulaire): self
    {
        $this->titulaire = $titulaire;

        return $this;
    }
}
