<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\PlageHoraire;
use App\Entity\PlanningType;
use App\Form\EtatType;
use App\Form\PlageHoraireType;
use App\Form\PlanningFormType;
use App\Repository\EtatRepository;
use App\Repository\PlageHoraireRepository;
use App\Repository\PlanningTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/add/{idEmploye}', name: 'addPlageHoraire')]
    public function addPlageHoraire(
        PlageHoraireRepository $plageHoraireRepository,
        PlanningTypeRepository $planningTypeRepository,
        EtatRepository $etatRepository,
        Request $request,
        int $idEmploye
    ): Response
    {
        // Récupérer le planningType correspondant à l'employé associé à l'ID
        $planningType = $planningTypeRepository->findByEmployeId($idEmploye);

        $plageHoraire = new PlageHoraire();
        $plageHoraire->setPlanningType($planningType); // Associer le planningType à la nouvelle plage horaire
        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);

        $plageHoraireForm->handleRequest($request);

        // Si le formulaire est soumis et valide, enregistrer les données en base de données
        if ($plageHoraireForm->isSubmitted() && $plageHoraireForm->isValid()) {
            $plageHoraire = $plageHoraireForm->getData();

            // Récupérer l'état correspondant au champ 'etats' du formulaire
            $etat = $plageHoraireForm->get('etats')->getData();
            $plageHoraire->setEtat($etat);

            // Enregistrer les données dans les différentes tables de la base de données
            $planningTypeRepository->save($planningType, true);
            $plageHoraireRepository->save($plageHoraire, true);

            $this->addFlash('success', "Plage Horaire Ajoutée !");
            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/add.html.twig', [
            'plageHoraire' => $plageHoraire,
            'plageHoraireForm' => $plageHoraireForm->createView()
        ]);
    }

//    #[Route('/add/{idEmploye}', name: 'addPlageHoraire')]
//    public function addPlageHoraire(
//        PlageHoraireRepository $plageHoraireRepository,
//        PlanningTypeRepository $planningTypeRepository,
//        Request $request,
//        int $idEmploye
//    ): Response
//    {
//        // Récupérer le planningType correspondant à l'employé associé à l'ID
//        $planningType = $planningTypeRepository->findOneBy(['idEmploye' => $idEmploye]);
//
//        $plageHoraire = new PlageHoraire();
//        $plageHoraire->setPlanningType($planningType); // Associer le planningType à la nouvelle plage horaire
//        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);
//
//        $plageHoraireForm->handleRequest($request);
//
//        // Si le formulaire est soumis et valide, enregistrer les données en base de données
//        if ($plageHoraireForm->isSubmitted() && $plageHoraireForm->isValid()) {
//            $plageHoraireRepository->save($plageHoraire, true);
//
//            $this->addFlash('success', "Plage Horaire Ajoutée !");
//            return $this->redirectToRoute('main_home');
//        }
//
//        return $this->render('main/add.html.twig', [
//            'plageHoraire' => $plageHoraire,
//            'plageHoraireForm' => $plageHoraireForm->createView()
//        ]);
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
//        return new JsonResponse([
//            'planningType' => $planningType,
//            'plagesHoraires' => $plagesHoraires,
//            'etats' => $etats,
//        ]);
//        return $this->render('main/showPlanning.html.twig', [
//            'planningType' => $planningType,
//            'plagesHoraires' => $plagesHoraires,
//            'etats' => $etats,
//        ]);
    }}





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




//
//    #[Route('/add/{idEmploye}', name: 'addPlageHoraire')]
//    public function addPlageHoraire(PlageHoraireRepository $plageHoraireRepository, Request $request, int $idEmploye): Response
//    {
//        //Formulaire pour ajouter un planning
//        $plageHoraire = new PlageHoraire();
//        $planningType = new PlanningType();
//        $planningType->setIdEmploye($idEmploye);
//        $plageHoraire->setPlanningType($planningType);
//        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);
//        $plageHoraireForm->handleRequest($request);
//
//        //si soumis rentre en BDD
//        if ($plageHoraireForm->isSubmitted() && $plageHoraireForm->isValid()) {
//            $plageHoraireRepository->save($plageHoraire, true);
//
//            $this->addFlash('success', "Plage Horaire Ajoutée !");
//            return $this->redirectToRoute('main_home');
//        }
//
//        return $this->render('main/add.html.twig', [
//            'plageHoraire' => $plageHoraire,
//            'plageHoraireForm' => $plageHoraireForm->createView()
//        ]);
//    }



