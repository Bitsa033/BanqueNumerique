<?php

namespace App\Services;

use App\Repository\ClientRepository;
use App\Repository\CompteRepository;
use App\Repository\HistoriqueTRepository;
use App\Repository\TypeCompteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataBaseService extends AbstractController
{

    protected $table;
    protected $repo;
    protected $compte_repo;
    protected $client_repo;
    protected $histo_repo;
    protected $type_c_repo;
    protected $db;

    public function __construct(
        CompteRepository $compteRepository,
        ClientRepository $clientRepository,
        HistoriqueTRepository $historiqueTRepository,
        TypeCompteRepository $typeCompteRepository
    ) {
        $this->compte_repo = $compteRepository;
        $this->client_repo = $clientRepository;
        $this->histo_repo = $historiqueTRepository;
        $this->type_c_repo = $typeCompteRepository;
    }

    /**
     * Cette méthode retourne le gestionnaire de connexion
     * à la base de données
     */
    public function getConnect()
    {
        return $this->db = $this->getDoctrine()->getManager();
    }

    /**
     * Cette méthode persiste les données dans la table courante
     * @param $object
     * @return void
     */
    public function save($object)
    {
        $this->db = $this->getConnect();
        $this->db->persist($object);
        $this->db->flush();
    }

    public function delete($object)
    {
        $this->db = $this->getConnect();
        $this->db->remove($object);
    }
}
