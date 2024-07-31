<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Entity\TypeCompte;
use App\Repository\CompteRepository;
use App\Services\ClientService;
use App\Services\CompteService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends CompteService
{

    /**
     * lien pour afficher tous les comptes
     * @Route("listeComptes", name="listeComptes")
     */
    function listeComptes(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }
        // $data = $this->compte_repo->findBy(["statut" => 1]);
        $data = $this->compte_repo->findAll();

        // dd($data);
        return $this->render("compte/listeComptes.html.twig", [
            'comptes' => $data
        ]);
    }

    /**
     * lien pour créer un nouveau uncompte
     * @Route("nouveauCompte", name="nouveauCompte")
     */
    function nouveauCompte(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $type_compte = $this->type_c_repo->findAll();
        if ($type_compte == null) {
            $cc = new TypeCompte();
            $cc->setNom("Compte courrant");
            $ce = new TypeCompte();
            $ce->setNom("Compte épargne");
            $cb = new TypeCompte();
            $cb->setNom("Compte bloqué");
            $this->save($cc);
            $this->save($ce);
            $this->save($cb);
        }

        return $this->render("compte/nouveauCompte.html.twig", [
            "type_compte" => $this->type_c_repo->findAll(),
            "clients" => $this->client_repo->findAll()
        ]);
    }

    /**
     * lien pour enregistrer un nouveau compte
     * @Route("nouveauCompteB", name="nouveauCompteB")
     */
    public function nouveauCompteB(Request $request, ClientService $service)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $nom = $request->request->get('nom');
        $contact = $request->request->get('contact');
        $email = $request->request->get('email');
        $pays = $request->request->get('pays');
        $ville = $request->request->get('ville');
        $quartier = $request->request->get('quartier');
        $adresse = $pays . "-" . $ville . "-" . $quartier;
        $type_compte_req = $request->request->get('type');
        $type_compte = $this->type_c_repo->find($type_compte_req);

        $client_nom = $this->client_repo->findOneBy(["nom" => $nom]);
        $client_email = $this->client_repo->findOneBy(["email" => $email]);
        if ($client_nom == null && $client_email == null) {
            // dd('null');
            $service->creer_compte(compact("nom", "email", "contact", "adresse", "type_compte"));
            $this->addFlash('success', 'Création du compte réussi !');
            return $this->redirect('nouveauCompte');
        } else {
            // dd($compte->getNumero());
            $this->addFlash('not_save_mismatch_client', "Nous ne pouvons pas créer le compte 
            car le nom ou l'email renseigné se trouve déja dans notre base de données!");
            return $this->redirect('nouveauCompte');
        }
    }

    /**
     * lien pour enregistrer un nouveau compte pour l'ancien client
     * @Route("nouveauCompteB2", name="nouveauCompteB2")
     */
    public function nouveauCompteB2(Request $request, ClientService $service)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $client = $request->request->get('client');
        $type_compte_req = $request->request->get('type');
        $type_compte = $this->type_c_repo->find($type_compte_req);

        $compte = $this->compte_repo->findOneBy(["type" => $type_compte_req, "client" => $client]);
        if ($compte == null) {
            // dd('null');
            $service->creer_compteB2(compact("client", "type_compte"));
            $this->addFlash('success', 'Création du compte réussi !');
            return $this->redirect('nouveauCompte');
        } else {
            // dd($compte->getNumero());
            $this->addFlash('not_save_mismatch_acount', 'Impossible de créer deux types de comptes identiques pour le meme client!');
            return $this->redirect('nouveauCompte');
        }
    }

    /**
     * lien pour crediter un compte
     * @Route("crediterCompte", name="crediterCompte")
     */
    function crediterCompte(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        return $this->render("compte/crediterCompte.html.twig", []);
    }

    /**
     * lien pour crediter un compte
     * @Route("crediterCompteB", name="crediterCompteB")
     */
    function crediterCompteB(Request $request, CompteRepository $c, BackController $backController)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }


        $nom = "Dépot";
        $montant = $request->request->get('montant');
        $numero_compte = $request->request->get("id_compte");
        $compte = $c->getIdByNumeroCompte($numero_compte);
        $id_compte = $compte->getId();
        $titulaire = $compte->getClient()->getNom();
        $statut = $compte->getStatut();
        if ($statut == 0) {
            $this->addFlash('statut_off', "Opération annuléee car ce compte est bloqué");
        } else {
            $backController->depotCompte($id_compte, $montant);
            $this->creerHistorique($nom, $montant, $numero_compte, $titulaire);
            // $this->addFlash('success', $backController->success);
            $this->addFlash('success', "Dépot éffectué");
        }

        return $this->redirect("crediterCompte");
    }

    /**
     * lien pour debiter un compte
     * @Route("debiterCompte", name="debiterCompte")
     */
    function debiterCompte(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        return $this->render("compte/debiterCompte.html.twig", []);
    }

    /**
     * lien pour débiter un compte
     * @Route("debiterCompteB", name="debiterCompteB")
     */
    function debiterCompteB(Request $request, BackController $backController, CompteRepository $c)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $nom = "Retrait";
        $numero_compte = $request->request->get("id_compte");
        $montant = $request->request->get('montant');
        $compte = $c->getIdByNumeroCompte($numero_compte);
        $solde = $this->compte_repo->findOneBy(["numero" => $numero_compte])->getSolde();
        $id_compte = $compte->getId();
        $titulaire = $compte->getClient()->getNom();
        $statut = $compte->getStatut();
        if ($statut == 0) {
            $this->addFlash('statut_off', "Opération annuléee car ce compte est bloqué");
        } elseif ($solde < $montant) {
            $this->addFlash('amount_insuficient', "Opération annuléee car le solde est insuffisant!");
        } else {
            $backController->retraitCompte($id_compte, $montant);
            $this->creerHistorique($nom, $montant, $numero_compte, $titulaire);
            $this->addFlash('success', "Retrait éffectué");
        }

        return $this->redirect("debiterCompte");
    }

    /**
     * lien pour transferer de l'argent
     * @Route("virementBancaire", name="virementBancaire")
     */
    function virementBancaire(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        return $this->render("compte/virementBancaire.html.twig", []);
    }

    /**
     * lien pour transferer de l'argent d 'un compte à un autre
     * @Route("virementBancaireB", name="virementBancaireB")
     */
    function virementBancaireB(Request $request, BackController $backController, CompteRepository $c)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("app_login");
        }
        $nom_debit = "Retrait";
        $nom_credit = "Dépot";
        $numero_compte_debit = $request->request->get("num_compte_debit");
        $numero_compte_credit = $request->request->get("num_compte_credit");
        $montant = $request->request->get("montant");
        $compte_debit = $c->getIdByNumeroCompte($numero_compte_debit);
        $id_compte_debit = $compte_debit->getId();
        $titulaire_compte_debit = $compte_debit->getClient()->getNom();
        $compte_credit = $c->getIdByNumeroCompte($numero_compte_credit);
        $id_compte_credit = $compte_credit->getId();
        $titulaire_compte_credit = $compte_credit->getClient()->getNom();
        $statut_compte_debit = $compte_debit->getStatut();
        $statut_compte_credit = $compte_credit->getStatut();
        $solde = $this->compte_repo->findOneBy(["numero" => $numero_compte_debit])->getSolde();

        if ($statut_compte_debit == 0  || $statut_compte_credit == 0) {
            $this->addFlash('statut_off', "Opération annuléee car l'un des comptes renseignés est bloqué");
        } elseif ($solde < $montant) {
            $this->addFlash('amount_insuficient', "Opération annuléee car le solde du compte du débiteur est insuffisant!");
        } else {
            $backController->virerArgent($id_compte_debit, $montant, $id_compte_credit);
            // $this->creerHistorique($data_crediteur);
            $this->creerHistorique($nom_debit, $montant, $numero_compte_debit, $titulaire_compte_debit);
            $this->creerHistorique($nom_credit, $montant, $numero_compte_credit, $titulaire_compte_credit);
            $this->addFlash('success', "Transfert éffectué");
        }

        return $this->redirect("virementBancaire");
    }

    /**
     * lien pour bloquer le compte
     * @Route("bloquerCompte", name="bloquerCompte")
     */
    function bloquerCompte(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        return $this->render("compte/bloquerCompte.html.twig", []);
    }

    /**
     * lien pour débiter un compte
     * @Route("bloquerCompteB", name="bloquerCompteB")
     */
    function bloquerCompteB(Request $request, BackController $backController, CompteRepository $c)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $numero_compte = $request->request->get("id_compte");
        $compte = $c->getIdByNumeroCompte($numero_compte);
        $id_compte = $compte->getId();

        $backController->desactiver($id_compte);
        $this->addFlash('success', "Compte désactivé avec succèss");
        return $this->redirect("bloquerCompte");
    }

    /**
     * lien pour activer le compte
     * @Route("activerCompte", name="activerCompte")
     */
    function activerCompte(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        return $this->render("compte/activerCompte.html.twig", []);
    }

    /**
     * lien pour débiter un compte
     * @Route("activerCompteB", name="activerCompteB")
     */
    function activerCompteB(Request $request, BackController $backController, CompteRepository $c)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $numero_compte = $request->request->get("id_compte");
        $compte = $c->getIdByNumeroCompte($numero_compte);
        $id_compte = $compte->getId();

        $backController->activer($id_compte);
        $this->addFlash('success', "Compte activé avec succèss");
        return $this->redirect("activerCompte");
    }

    /**
     * lien pour activer le compte
     * @Route("imprimer_compte_menu", name="imprimer_compte_menu")
     */
    function imprimer_compte_menu(Request $request, SessionInterface $sessionInterface): Response
    {
        $print_select = $request->request->get("option_impression");
        $client = $request->request->get("client");
        $client_session = $sessionInterface->get("client", []);
        if (!empty($print_select) && !empty($client)) {
            if (empty($client_session)) {
                $sessionInterface->set("client", $client);
                if ($print_select == "releve_bancaire") {
                    return $this->redirect("releve_bancaire");
                } elseif ($print_select == "releve_transactions") {
                    return $this->redirect("releve_bancaire");
                }
            }
            $sessionInterface->set("client", $client);
            if ($print_select == "releve_bancaire") {
                return $this->redirect("releve_bancaire");
            } elseif ($print_select == "releve_transactions") {
                return $this->redirect("releve_bancaire");
            }
        }

        return $this->render("impressions/imprimer_compte_menu.html.twig", [
            "clients" => $this->client_repo->findAll()
        ]);
    }

    /**
     * @Route("releve_bancaire", name="releve_bancaire")
     */
    function releve_bancaire(SessionInterface $sessionInterface)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $client_session = $sessionInterface->get("client", []);
        // dd($client_session);
        $client = $this->client_repo->find($client_session);
        $nom_client = $client->getNom();
        $data = $this->compte_repo->findBy(["client" => $client]);

        return $this->render("impressions/releve_bancaire.html.twig", [
            "comptes" => $data,
            "nom_client" => $nom_client
        ]);
    }

    /**
     * @Route("releve_transactions", name="releve_transactions")
     */
    function releve_transactions(SessionInterface $sessionInterface)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $client_session = $sessionInterface->get("client", []);
        // dd($client_session);
        $client = $this->client_repo->find($client_session);
        $nom_client = $client->getNom();
        $data = $this->histo_repo->findBy(["titulaire" => $nom_client]);

        return $this->render("impressions/releve_transactions.html.twig", [
            "transactions" => $data,
            "nom_client" => $nom_client
        ]);
    }
}
