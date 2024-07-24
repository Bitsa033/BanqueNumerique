<?php

namespace App\Services;

interface CompteInterface
{

    function creerHistorique($nomTransaction, $montant, $numero_compte, $titulaire);
    function crediter($numeroCompte, $montant);
    function debiter($numeroCompte, $montant);
    function virerMontant($numeroCompteDebiteur, $montant, $numeroCompteCrediteur);

    function supprimer($numeroCompte);
    function activer($numeroCompte);
    function desactiver($numeroCompte);
}
