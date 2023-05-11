<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\PlageHoraire;
use App\Form\EtatType;
use App\Form\PlageHoraireType;
use App\Repository\EtatRepository;
use App\Repository\PlageHoraireRepository;
use App\Repository\PlanningTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        return $this->render('main/index.html.twig');
    }


    #[Route('/add', name: 'addPlageHoraire')]
    public function addPlageHoraire(PlageHoraireRepository $plageHoraireRepository, Request $request): Response
    {
        //Formulaire pour ajouter un planning
        //TODO revoir ajout d'un etat par plage horaire et non pour la journée
        $plageHoraire = new PlageHoraire();
        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);
        $plageHoraireForm->handleRequest($request);


        //si soumis rentre en BDD
        if ($plageHoraireForm->isSubmitted()) {
            $plageHoraireRepository->save($plageHoraire, true);

            //TODO Affiche message si bien enristré en BDD
            $this->addFlash('succes', "Plage Horaire Ajoutée !");
            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/add.html.twig', ['plageHoraire' => $plageHoraire,
            'plageHoraireForm' => $plageHoraireForm->createView()
        ]);

    }
//Retourne l'employé et le planning sous forme de vue html et css
//    #[Route('/showPlanning/{id}', name: 'showPlanning', requirements: ['id' => '\d+'])]
//    public function showPlanningUser(int $id, PlanningTypeRepository $planningTypeRepository, PlageHoraireRepository $plageHoraireRepository, EtatRepository $etatRepository, HttpClientInterface $client): Response
//    {
//        // Récupérer les données de l'employé
//        $employe = $this->getEmploye($client, $id);
//
//        // Récupérer les données du planning type
//        $planningType = $planningTypeRepository->find($id);
//
//        // Récupérer les plages horaires pour le planning type donné
//        $plagesHoraires = $planningType->getPlagesHoraires();
//
//        // Récupérer les états liés à chaque plage horaire
//        $etats = [];
//        foreach ($plagesHoraires as $plageHoraire) {
//            $etats[$plageHoraire->getId()] = $etatRepository->findByPlageHoraires($plageHoraire);
//        }
//
//        // Fusionner les tableaux associatifs des données
//        $data = array_merge([
//            'employe' => $employe,
//            'planningType' => $planningType,
//            'plagesHoraires' => $plagesHoraires,
//            'etats' => $etats,
//        ]);
//
//        return $this->render('main/showPlanning.html.twig', $data);
//
//    }

////retourne l'employé et le planning en Json mais le planning est vide
//    #[Route('/showPlanning/{id}', name: 'showPlanning', requirements: ['id' => '\d+'])]
//    public function showPlanningUser(int $id, PlanningTypeRepository $planningTypeRepository, PlageHoraireRepository $plageHoraireRepository, EtatRepository $etatRepository, HttpClientInterface $client): JsonResponse
//    {
//        // Récupérer les données de l'employé
//        $employe = $this->getEmploye($client, $id);
//
//        // Récupérer les données du planning type
//        $planningType = $planningTypeRepository->find($id);
//
//        // Récupérer les plages horaires pour le planning type donné
//        $plagesHoraires = $planningType->getPlagesHoraires();
//
//        // Récupérer les états liés à chaque plage horaire
//        $etats = [];
//        foreach ($plagesHoraires as $plageHoraire) {
//            $etats[$plageHoraire->getId()] = $etatRepository->findByPlageHoraires($plageHoraire);
//        }
//
//        // Fusionner les tableaux associatifs des données
//        $data = array_merge([
//            'employe' => $employe,
//            'planningType' => $planningType,
//            'plagesHoraires' => $plagesHoraires,
//            'etats' => $etats,
//        ]);
//
//       return new JsonResponse($data);
//
//    }
//    /**
//     * Récupère les données de l'employé depuis l'API
//     */
//    private function getEmploye(HttpClientInterface $client, int $id): array
//    {
//        $url = 'http://172.17.0.3/public/employe/' . $id;
//        $response = $client->request('GET', $url);
//
//        return json_decode($response->getContent(), true);
//    }

//Affiche uniquement le planning suivant son id
    #[Route('/showPlanning/{id}', name: 'showPlanning',requirements: ['id'=> '\d+'])]
    public function showPlanningUser(int $id, PlanningTypeRepository $planningTypeRepository, PlageHoraireRepository $plageHoraireRepository, EtatRepository $etatRepository ): Response
    {
        $planningType = $planningTypeRepository->find($id);
        // Récupérer les plages horaires pour le planning type donné
        $plagesHoraires = $planningType->getPlagesHoraires();

        // Récupérer les états liés à chaque plage horaire
        $etats = [];
        foreach ($plagesHoraires as $plageHoraire) {
            $etats[$plageHoraire->getId()] = $etatRepository->findByPlageHoraires($plageHoraire);
        }
        return $this->json([
            'planningType' => $planningType,
            'plagesHoraires' => $plagesHoraires,
            'etats' => $etats,
        ], 200, [], ['groups' => 'planning_api']);
        return new JsonResponse([
            'planningType' => $planningType,
            'plagesHoraires' => $plagesHoraires,
            'etats' => $etats,
        ]);
        return $this->render('main/showPlanning.html.twig', [
            'planningType' => $planningType,
            'plagesHoraires' => $plagesHoraires,
            'etats' => $etats,
        ]);
    }}


//    #[Route('/add', name: 'addPlageHoraire')]
//    public function addPlageHoraire(
//        PlageHoraireRepository $plageHoraireRepository,
//        PlanningTypeRepository $planningTypeRepository,
//        EtatRepository $etatRepository,
//        Request $request
//    ): Response {
//        // Formulaire pour ajouter une plage horaire
//        $plageHoraire = new PlageHoraire();
//        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);
//        $plageHoraireForm->handleRequest($request);
//
//        // Si le formulaire est soumis, ajouter la plage horaire à la BDD
//        if ($plageHoraireForm->isSubmitted() && $plageHoraireForm->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//
//            // Récupérer l'objet PlanningType correspondant
//            $planningTypeId = $plageHoraireForm->get('planningType')->getData();
//            $planningType = $planningTypeRepository->find($planningTypeId);
//
//            // Ajouter la plage horaire au PlanningType
//            $planningType->addPlageHoraire($plageHoraire);
//            $em->persist($planningType);
//
//            // Récupérer l'objet Etat correspondant
//            $etatId = $plageHoraireForm->get('etat')->getData();
//            $etat = $etatRepository->find($etatId);
//
//            // Ajouter l'Etat à la PlageHoraire
//            $plageHoraire->setEtat($etat);
//            $em->persist($plageHoraire);
//
//            $em->flush();
//
//            // Afficher un message de succès et rediriger vers la page d'accueil
//            $this->addFlash('success', 'Plage horaire ajoutée avec succès !');
//            return $this->redirectToRoute('main_home');
//        }
//
//        return $this->render('main/add.html.twig', [
//            'plageHoraire' => $plageHoraire,
//            'plageHoraireForm' => $plageHoraireForm->createView()
//        ]);
//    }



