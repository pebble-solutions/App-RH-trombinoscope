<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/planning_type/', name: 'planning_type_')]
class PlanningTypeController extends AbstractController
{
    #[Route('/employe/{id}', name: 'retrieve_one',requirements: ['id'=> '\d+'], methods: ["GET"])]
    public function showEmployePlanning(string $id): Response
    {
        $id = intval($id); // convertir $id en entier
        $url = 'http://172.17.0.2/public/employe/'.$id;
        $data = file_get_contents($url);
        $employe = json_decode($data, true);

        return $this->render('planning_type/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    #[Route('/employe/{id}', name: 'retrieve_one_httpclient', methods: ["GET"])]
    public function showEmployePlanningWithHttpClient(HttpClientInterface $httpClient, int $id): Response
    {
        // Appel de l'API pour récupérer les données de l'employé correspondant à l'ID
        $response = $httpClient->request('GET', 'http://172.17.0.2/public/planning_type/employe/'.$id);

        // Vérification de la réponse
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Erreur lors de la récupération des données de l\'employé');
        }

        // Récupération des données de l'employé au format JSON
        $employeJson = $response->getContent();

        // Conversion du JSON en tableau associatif
        $employe = json_decode($employeJson, true);

        // Affichage des données de l'employé
        return $this->render('planning_type/show.html.twig', [
            'employe' => $employe,
        ]);
    }


//    #[Route('/employes', name: 'retrieve_all', methods: ["GET"])]
//    public function showAllEmployes(): Response
//    {
//        $url = 'http://172.17.0.2/public/planning_type/employes';
//        $data = file_get_contents($url);
//        $employes = json_decode($data, true);
//
//        return $this->render('planning_type/show_all.html.twig', [
//            'employes' => $employes,
//        ]);
//    }
//    #[Route('/employes', name: 'retrieve_all2', methods: ["POST"])]
//    public function showAllEmployes2(): Response
//    {
//        $url = 'http://172.17.0.2/public/planning_type/employes';
//        $options = array(
//            'http' => array(
//                'method'  => 'POST',
//                'content' => json_encode(array('foo' => 'bar')),
//                'header' =>  "Content-Type: application/json\r\n" .
//                    "Accept: application/json\r\n"
//            )
//        );
//        $context  = stream_context_create($options);
//        $data = file_get_contents($url, false, $context);
//        $employes = json_decode($data, true);
//
//        return $this->render('planning_type/show_all.html.twig', [
//            'employes' => $employes,
//        ]);
//}

//    #[Route('/{id}', name: 'retrieve_one', methods: ["POST"])]
//    public function showEmployePlanning(int $id): Response
//    {
//        $url = 'http://172.17.0.2/public/planning_type/employe/'.$id;
//        $data = file_get_contents($url);
//        $employe = json_decode($data, true);
//
//        return $this->render('planning_type/show.html.twig', [
//            'employe' => $employe,
//        ]);
//    }
}
