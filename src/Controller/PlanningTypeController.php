<?php

namespace App\Controller;

use App\Controller\Api\EmployeController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/planning_type/', name: 'planning_type_')]
class PlanningTypeController extends AbstractController
{
    #[Route('/employe/{id}', name: 'retrieve_one', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function showEmployePlanning(int $id, HttpClientInterface $client): Response
    {
        // Récupérer les données de l'employé
        $employe = $this->getEmploye($client, $id);

        return $this->render('planning_type/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    /**
     * Récupère les données de l'employé depuis l'API
     */
    private function getEmploye(HttpClientInterface $client, int $id): array
    {
        $url = 'http://172.17.0.3/public/employe/' . $id;
        $response = $client->request('GET', $url);

        return json_decode($response->getContent(), true);
    }

//
//    /**
//     * Récupère les données du planning depuis l'API
//     */
//    private function getPlanning(HttpClientInterface $client, int $id): array
//    {
//        $url = 'http://172.17.0.3/public/showPlanning/' . $id;
//        $response = $client->request('GET', $url);
//
//        return json_decode($response->getContent(), true);
//    }


//
//#[Route('/planning_type/', name: 'planning_type_')]
//class PlanningTypeController extends AbstractController
//{
//    #[Route('/employe/{id}', name: 'retrieve_one',requirements: ['id'=> '\d+'], methods: ["GET"])]
//    public function showEmployePlanning(string $id): Response
//    {
//        $id = intval($id); // convertir $id en entier
//        $url = 'http://172.17.0.2/public/employe/'.$id;
//        $data = file_get_contents($url);
//        $employe = json_decode($data, true);
//
//        return $this->render('planning_type/show.html.twig', [
//            'employe' => $employe,
//        ]);
//    }
//
//
}
