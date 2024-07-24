<?php

namespace App\Services;

use App\Entity\Compte;
use App\Entity\HistoriqueT;
use App\Repository\CompteRepository;
use DateTime;

class CompteService  extends DataBaseService implements CompteInterface
{

  public function creerHistorique($nomTransaction, $montant, $numero_compte, $titulaire)
  {
    $h = new HistoriqueT();
    $h->setNom($nomTransaction);
    $h->setDateT(new DateTime());
    $h->setSolde($montant);
    $h->setNumeroCompte($numero_compte);
    $h->setTitulaire($titulaire);
    $this->save($h);
  }

  function debiter($numeroCompte, $montant)
  {
    $c = $this->compte_repo->find($numeroCompte);
    $solde_courant = $c->getSolde();
    $solde_actuel = $solde_courant - $montant;
    $c->setSolde($solde_actuel);
    $this->save($c);
  }

  public function crediter($numeroCompte, $montant)
  {
    $c = $this->compte_repo->find($numeroCompte);
    $solde_courant = $c->getSolde();
    $solde_actuel = $solde_courant + $montant;
    $c->setSolde($solde_actuel);
    $this->save($c);
  }

  public function virerMontant($numeroCompteDebiteur, $montant, $numeroCompteCrediteur)
  {
    $this->debiter($numeroCompteDebiteur, $montant);
    $this->crediter($numeroCompteCrediteur, $montant);
  }

  public function activer($numeroCompte)
  {
    $c = $this->compte_repo->find($numeroCompte);
    $c->setStatut(1);
    $this->save($c);
  }

  public function desactiver($numeroCompte)
  {
    $c = $this->compte_repo->find($numeroCompte);
    $c->setStatut(0);
    $this->save($c);
  }

  public function supprimer($numeroCompte)
  {
  }
}
