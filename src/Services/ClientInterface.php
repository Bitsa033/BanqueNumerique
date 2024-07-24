<?php

namespace App\Services;

interface ClientInterface
{

    function creer_compte(array $data);
    function modifier_client(array $data);
    function afficher_client($id);
    function supprimer_client($id);
}
