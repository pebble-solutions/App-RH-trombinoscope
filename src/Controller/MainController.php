<?php

namespace App\Controller;

use App\Entity\PlageHoraire;
use App\Form\PlageHoraireType;
use App\Repository\EtatRepository;
use App\Repository\PlageHoraireRepository;
use App\Repository\PlanningTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
        EtatRepository         $etatRepository,
        Request                $request,
        int                    $idEmploye
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
            $plageHoraire->setEtats($etat);

            // Enregistrer les données dans les différentes tables de la base de données
            $planningTypeRepository->save($planningType, true);
            $plageHoraireRepository->save($plageHoraire, true);
            $etatRepository->save($etat, true);

            $this->addFlash('success', "Plage Horaire Ajoutée !");
            return $this->redirectToRoute('main_home');
        }

        return $this->render('main/add.html.twig', [
            'plageHoraire' => $plageHoraire,
            'plageHoraireForm' => $plageHoraireForm->createView()
        ]);
    }

    #[Route('/edit/{idPlageHoraire}', name: 'editPlageHoraire')]
    public function updatePlageHoraire(
        PlageHoraireRepository $plageHoraireRepository,
        PlanningTypeRepository $planningTypeRepository,
        EtatRepository         $etatRepository,
        Request                $request,
        int                    $idPlageHoraire
    ): Response
    {
        $plageHoraire = $plageHoraireRepository->find($idPlageHoraire);

        $planningType = $plageHoraire->getPlanningType();

        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);

        $plageHoraireForm->handleRequest($request);

        if ($plageHoraireForm->isSubmitted() && $plageHoraireForm->isValid()) {
            $plageHoraire = $plageHoraireForm->getData();

            $etat = $plageHoraireForm->get('etats')->getData();
            $plageHoraire->setEtat($etat);

            $planningTypeRepository->save($planningType, true);
            $plageHoraireRepository->save($plageHoraire, true);
            $etatRepository->save($etat, true);

            if ($request->isXmlHttpRequest()) {
                // Créer un tableau associatif contenant les données mises à jour
                $data = [
                    'id' => $plageHoraire->getId(),
                    'debut' => $plageHoraire->getDateDebut()->format('Y-m-d H:i:s'),
                    'fin' => $plageHoraire->getDateFin()->format('Y-m-d H:i:s'),
                    'nom_etat' => $etat->getLibelle(),
                ];

                // Renvoyer les données sous forme de JSON
                return new JsonResponse($data);
            } else {
                // Si la requête n'est pas une requête AJAX, rediriger vers la page d'accueil
                $this->addFlash('success', "Plage Horaire Modifiée !");
                return $this->redirectToRoute('main_home');
            }
        }

        return $this->render('main/edit.html.twig', [
            'plageHoraire' => $plageHoraire,
            'plageHoraireForm' => $plageHoraireForm->createView()
        ]);
    }


//Affiche uniquement le planning suivant son id
    #[Route('/showPlanning/{id}', name: 'showPlanning', requirements: ['id' => '\d+'])]
    public function showPlanningUser(int $id, PlanningTypeRepository $planningTypeRepository, PlageHoraireRepository $plageHoraireRepository, EtatRepository $etatRepository): Response
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
    }
}




