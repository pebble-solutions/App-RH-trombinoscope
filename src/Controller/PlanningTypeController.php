<?php

namespace App\Controller;

use App\Controller\Api\EmployeController;
use App\Entity\Etat;
use App\Entity\PlageHoraire;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\PlanningType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('', name: 'adddata', methods: ["POST"])]
    public function addData(Request $request, EntityManagerInterface $entityManager)
    {
        $data = json_decode($request->getContent(), true);

        // Creation du planning type
        $planningType = new PlanningType();
        $planningType->setIdEmploye($data['planningType']['idEmploye']);
        $planningType->setInom($data['planningType']['inom']);


        $plagesHoraires = $data['plagesHoraires'];
        foreach ($plagesHoraires as $plage) {
            $plageHoraire = new PlageHoraire();
            $plageHoraire->setNomPlage($plage['nom_plage']);
            $plageHoraire->setDebut(new DateTime($plage['debut']));
            $plageHoraire->setFin(new DateTime($plage['fin']));
            $plageHoraire->setNumJour($plage['num_jour']);
            $plageHoraire->setPlanningType($planningType);
            $entityManager->persist($plageHoraire);
        }

        // Create and associate the etat entities
        $etatsData = $data['etats'];
        foreach ($etatsData as $etatData) {
            $etat = new Etat();
            $etat->setNomEtat($etatData['nom_etat']);
            $entityManager->persist($etat);

            foreach ($plagesHoraires as $plage) {
                if ($plage['id'] == $etatData['id']) {
                    $plageHoraire = $entityManager->getRepository(PlageHoraire::class)->find($plage['id']);
                    $plageHoraire->setEtat($etat);
                    break;
                }
            }
        }

        $entityManager->persist($planningType);
        $entityManager->flush();

        return new JsonResponse(['status' => 'success']);
    }

}
