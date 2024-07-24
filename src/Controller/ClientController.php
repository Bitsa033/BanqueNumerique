<?php

namespace App\Controller;

use App\Services\ClientService;
use App\Services\DataBaseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * lien pour modifier un client qu'on a déja enregistré
     * @Route("updateClient", name="updateClient")
     */
    function modifier_client(Request $request, ClientService $service)
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
    }
}
