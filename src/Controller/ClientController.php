<?php

namespace App\Controller;

use App\Services\ClientService;
use App\Services\DataBaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends DataBaseService
{
    /**
     * lien qui affiche la liste des clients
     * @Route("clients", name="clients")
     */
    public function clients(ClientService $service): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        // dd($d=$clients->readOneData(8));
        return $this->render('client/index.html.twig', [
            'clients' => $service->client_repo->findAll()
        ]);
    }

    /**
     * lien qui affiche la liste des clients
     * @Route("find_client", name="find_client")
     */
    public function find(Request $request, SessionInterface $sessionInterface): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        $client = $request->request->get("client");
        $client_session = $sessionInterface->get("client", []);
        if (!empty($client)) {
            if (empty($client_session)) {
                $sessionInterface->set("client", $client);
                return $this->redirect("edit_client");
                // dd($request);
            }
            $sessionInterface->set("client", $client);
            return $this->redirect("edit_client");
            // dd($request);
        }
        return $this->render('client/find.html.twig', [
            "clients" => $this->client_repo->findAll()
        ]);
    }

    /**
     * lien qui affiche la liste des clients
     * @Route("edit_client", name="edit_client")
     */
    public function edit(Request $request, SessionInterface $sessionInterface): Response
    {
        $client_session = $sessionInterface->get("client", []);
        if (empty($client_session)) {
            // dd($client_session);
        }
        // dd($client_session);
        $client = $this->client_repo->find($client_session);
        // $nom_client = $client->getNom();
        // $data = $this->histo_repo->findBy(["titulaire" => $nom_client]);

        return $this->render('client/edit.html.twig', [
            'client' => $client
        ]);
    }

    /**
     * lien qui affiche la liste des clients
     * @Route("updateClient", name="updateClient")
     */
    function updateClient(Request $request, SessionInterface $sessionInterface)
    {
        $client = $sessionInterface->get("client", []);
        // dd($client);
        $nom = $request->request->get("nom");
        $email = $request->request->get("email");
        $contact = $request->request->get("contact");
        $adresse = $request->request->get("adresse");
        $client_r = $this->client_repo->find($client);
        $client_r->setNom($nom);
        $client_r->setEmail($email);
        $client_r->setContact($contact);
        $client_r->setAdresse($adresse);
        $this->save($client_r);
        $this->addFlash('success', "Données modifiées avec succèss");
        return $this->redirect("edit_client");
    }



    /**
     * @Route("print_liste_clients", name="print_liste_clients")
     */
    function liste_clients()
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect("/");
        }

        return $this->render("impressions/liste_clients.html.twig", [
            "clients" => $this->client_repo->findAll(),
        ]);
    }
}
