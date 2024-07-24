<?php

namespace App\Services;

use App\Entity\Client;
use App\Entity\Compte;

class ClientService extends DataBaseService implements ClientInterface
{

  public function creer_compte(array $data)
  {
    $table = new Client();
    $table->setNom($data["nom"]);
    $table->setEmail($data["email"]);
    $table->setContact($data["contact"]);
    $table->setAdresse($data["adresse"]);

    $c = new Compte();
    //numero de compte
    $chaine_a = rand(012, 345);
    $chaine_b = rand(678, 910);
    $chaine_c = rand(111, 213);
    $chaine_d = rand(145, 6177);
    $c->setNumero($chaine_a . " " . $chaine_b . " " . $chaine_c . " " . $chaine_d);
    $c->setRib($chaine_b);
    $c->setStatut(1);
    $c->settype($data["type_compte"]);
    $c->setSolde(0);
    $c->setClient($table);
    $this->save($c);
  }

  public function creer_compteB2(array $data)
  {
    $client = $this->client_repo->find($data['client']);
    $c = new Compte();
    //numero de compte
    $chaine_a = rand(012, 345);
    $chaine_b = rand(678, 910);
    $chaine_c = rand(111, 213);
    $chaine_d = rand(145, 6177);
    $c->setNumero($chaine_a . " " . $chaine_b . " " . $chaine_c . " " . $chaine_d);
    $c->setRib($chaine_b);
    $c->setStatut(1);
    $c->settype($data["type_compte"]);
    $c->setSolde(0);
    $c->setClient($client);
    $this->save($c);
  }

  public function modifier_client(array $data)
  {
    $c = $this->client_repo->find($data["id_client"]);
    $c->setNom($data["nom"]);
    $c->setContact($data["contact"]);
    $c->setAdresse($data["adresse"]);
    $c->setEmail($data["email"]);
    $this->save($c);
  }

  public function afficher_client($id)
  {
  }

  public function supprimer_client($id)
  {
  }
}
