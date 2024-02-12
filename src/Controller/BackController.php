<?php

namespace App\Controller;

use App\Repository\CompteRepository;
use App\Services\CompteService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends CompteService
{
    public $message;
    public $numCompteRec;
    /**
     * lien pour afficher tous les comptes
     * @Route("/", name="comptes")
     */
    public function listeComptes(): Response
    {
        return $this->render('back/index.html.twig', [
            'comptes' => $this->getRepo()->findAll()
        ]);
    }

    /**
     * lien pour ajouter une somme dans le compte
     * @Route("depotCompte", name="depotCompte")
     */
    function depotCompte($id_compte, $montant)
    {
        if (!empty($id_compte) && !empty($montant)) { 

            $id_compte_db=$this->getId($id_compte);

            if (!$id_compte_db) {

                return $this->message = $this->json([
                    'message'=>'Erreur, Ce compte n\'existe pas dans notre base de données ! ',
                    'icon'=>'error',
                ]);
            }
            
            else {

                $this->crediter($id_compte_db,$montant);
                
                return $this->message = $this->json([
                    'message'=>'Ok, Dépot effectué avec success',
                    'icon'=>'success',
                ]);

                
            }

        }
        else {

            return $this->message = $this->json([
                'message'=>'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
                'icon'=>'error'
            ]);
        }
    }

    /**
     * lien pour ajouter une somme dans le compte
     * @Route("retraitCompte", name="retraitCompte")
     */
    function retraitCompte($id_compte,$montant)
    {
        
        //dd($solde_courant);
        if (!empty($id_compte) && !empty($montant)) { 

            $id_compte_db=$this->getRepo()->find($id_compte);
            $solde_courant=$id_compte_db->getSolde();

            if (!$id_compte_db) {

                return $this->message = $this->json([
                    'message'=>'Erreur, Ce compte n\'existe pas dans notre base de données ! ',
                    'icon'=>'error',
                ]);
            }

            elseif ($solde_courant<$montant) {

                return $this->message = $this->json([
                    'message'=>'Erreur, Votre solde est insuffisant, veuillez recharger votre compte et recommencez ! ',
                    'icon'=>'error',
                ]);
            }
            
            else {
                $this->debiter($id_compte_db,$montant);
                
                return $this->message = $this->json([
                    'message'=>'Ok, Retrait effectué avec success',
                    'icon'=>'success',
                ]);
                
            }

        }
        else {
            return $this->message = $this->json([
                'message'=>'Erreur, Votre formulaire ne doit pas etre vide... Remplissez-le',
                'icon'=>'error'
            ]);
        }
    }

    /**
     * lien pour transferer de l'argent d'un compte à au autre
     * @Route("virerMontant", name="virerMontant")
     */
    function virer($num_compteDebiteur,$montant,$num_compteReceveur)
    {
        $repo=CompteRepository::class;
        // $id_post_deb=$request->request->get('id_post_deb');
        // $compteDeb=$this->getRepo()->find($id_post_deb);
        // $solde_courant=$compteDeb->getSolde();
        // $id_compteDebiteur_db=$this->getRepo()->find($id_compteDebiteur);
        // $this->numCompteRec=$id_compteReceveur_db;
        $this->numCompteRec=$num_compteReceveur;
        if (!empty($this->numCompteRec)) {
            $id_compteReceveur_db=$this->repo->getIdByNumero("2242");
            # code...
            dd($this->numCompteRec);
        }
        // return $this->message = $this->json([
        //     'message'=>'Ok, Transfert effectué avec success',
        //     'icon'=>'success',
        // ]);
    }

}
