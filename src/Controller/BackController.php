<?php

namespace App\Controller;

use App\Services\CompteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends CompteService
{
    public $success = "";
    public $error = "";
    public $invalid_input = "";

    function depotCompte($id_compte, $montant)
    {
        if (!empty($id_compte) && !empty($montant)) {
            $this->crediter($id_compte, $montant);

            // return $this->json([
            //     'message' => 'Ok, Dépot effectué avec success',
            //     'icon' => 'success',
            // ]);
            return $this->success = 'Ok, Dépot effectué avec success';
        } else {

            // return $this->json([
            //     'message' => 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
            //     'icon' => 'error'
            // ]);
            return $this->invalid_input = 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le';
        }
    }

    function retraitCompte($id_compte, $montant)
    {

        if (!empty($id_compte) && !empty($montant)) {
            $this->debiter($id_compte, $montant);

            // return $this->json([
            //     'message' => 'Ok, Dépot effectué avec success',
            //     'icon' => 'success',
            // ]);
            return $this->success = 'Ok, Retrait effectué avec success';
        } else {

            // return $this->json([
            //     'message' => 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
            //     'icon' => 'error'
            // ]);
            return $this->invalid_input = 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le';
        }
    }

    function virerArgent($compte_debiteur, $montant, $compte_receveur)
    {
        if (!empty($compte_debiteur) && !empty($montant) && !empty($compte_receveur)) {

            $this->virerMontant($compte_debiteur, $montant, $compte_receveur);

            return $this->success = 'Ok, Transfert effectué avec success';
        } else {

            // return $this->json([
            //     'message' => 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
            //     'icon' => 'error'
            // ]);
            return $this->invalid_input = 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le';
        }
    }

    function gelerCompte($numero_compte)
    {
        if (!empty($numero_compte)) {
            $this->desactiver($numero_compte);

            return $this->success = 'Ok, Le compte a été bloqué avec success';
        } else {

            // return $this->json([
            //     'message' => 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
            //     'icon' => 'error'
            // ]);
            return $this->invalid_input = 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le';
        }
    }

    function activerCompte($numero_compte)
    {
        if (!empty($numero_compte)) {

            $this->activer($numero_compte);

            return $this->success = 'Ok, Le compte a été activé avec success';
        } else {

            // return $this->json([
            //     'message' => 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
            //     'icon' => 'error'
            // ]);
            return $this->invalid_input = 'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le';
        }
    }
}
