<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\PlageHoraire;
use App\Entity\PlanningType;
use App\Repository\EtatRepository;
use App\Repository\PlageHoraireRepository;
use App\Repository\PlanningTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pebble\Security\PAS\PasToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

use Throwable;

class PlanningController extends AbstractController
{
    public function AuthToken()
    {
        try {
            $token = new PasToken();
            $token->getTokenFromAuthorizationHeader()->decode();
                return $token;
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException("Error : ".$e->getMessage());
        }
    }
    #[Route('/api/test', name: 'testapi', methods: ['GET'])]
    public function getTest(){
            $token = $this->AuthToken();
            return new JsonResponse(['succes' => $token->getLogin()]);
    }

//    #[Route('/api/test', name: 'testapi', methods: ['GET'])]
//    public function getTest()
//    {
//        try {
//            $token = new PasToken();
//            $token->getTokenFromAuthorizationHeader()->decode();
//            $content = $token->getLogin();
//
//            var_dump($content); // Ajoutez cette ligne pour vérifier les données
//
//            // Vérification de la propriété "name"
//            if (is_object($content) && isset($content->name)) {
//                $name = $content->name; // Accéder à la propriété "name"
//                return new Response($name);
//            } else {
//                // La propriété "name" n'est pas définie dans les données
//                throw new \Exception("La propriété 'name' n'est pas présente dans les données ou les données ne sont pas valides.");
//            }
//        } catch (\Throwable $e) {
//            $error = "Error: " . $e->getMessage();
//
//            return new Response($error, 500);
//        }
//    }



    #[Route('/api/plannings', name: 'app_planning', methods: ['GET'])]
    public function getAllPlannings(PlanningTypeRepository $planningTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        //$token = $this->AuthToken();
        $planningList = $planningTypeRepository->findAll();
        $jsonPlanningList = $serializer->serialize($planningList, 'json', ['groups' => 'planning_api']);
        return new JsonResponse($jsonPlanningList, Response::HTTP_OK, [], true);
    }


    #[Route('/api/plannings/{id}', name: 'detailPlanning', methods: ['GET'])]
    public function getDetailPlanning(PlanningType $planningType, SerializerInterface $serializer): JsonResponse
    {
        //$token = $this->AuthToken();
        $jsonPlanningType = $serializer->serialize($planningType, 'json', ['groups' => 'planning_api']);
        return new JsonResponse($jsonPlanningType, Response::HTTP_OK, ['accept' => 'json'], true);
    }


    #[Route('/api/plannings/{id}', name: 'deletePlanning', methods: ['DELETE'])]
    public function deletePlanning(PlanningType $planningType, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($planningType);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/plannings', name: 'createPlanning', methods: ['POST'])]
    public function createPlanning(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        UrlGeneratorInterface $urlGenerator,
        PlanningTypeRepository $planningTypeRepository,
        PlageHoraireRepository $plageHoraireRepository,
        EtatRepository $etatRepository
    ): JsonResponse {
        // Désérialisation des données JSON en un objet PlanningType
        $planningType = $serializer->deserialize($request->getContent(), PlanningType::class, 'json');

        // Récupération des données de la requête sous forme de tableau
        $data = $request->toArray();

        // Récupération des plages horaires et de leurs états
        $plagesHorairesData = $data['plagesHoraires'] ?? [];

        foreach ($plagesHorairesData as $plageHoraireData) {
            $nomPlage = $plageHoraireData['nomPlage'] ?? null;
            $debut = $plageHoraireData['debut'] ?? null;
            $fin = $plageHoraireData['fin'] ?? null;
            $numJour = $plageHoraireData['numJour'] ?? null;
            $etatData = $plageHoraireData['etat']['nomEtat'] ?? null;

            // Créez une nouvelle instance de PlageHoraire et d'Etat
            $plageHoraire = new PlageHoraire();
            $etat = new Etat();

            // Configurez les valeurs pour la plage horaire
            $plageHoraire->setNomPlage($nomPlage);
            $plageHoraire->setDebut(new \DateTime($debut));
            $plageHoraire->setFin(new \DateTime($fin));
            $plageHoraire->setNumJour($numJour);

            // Configurez les valeurs pour l'état
            $etat->setNomEtat($etatData);

            // Associez l'état à la plage horaire
            $plageHoraire->setEtat($etat);

            // Associez la plage horaire au planningType
            $planningType->addPlageHoraire($plageHoraire);

            // Persistez la plage horaire (pas besoin de persister l'état car il est cascade persist)
            $em->persist($plageHoraire);
        }

        $em->persist($planningType);
        $em->flush();

        $jsonPlanningType = $serializer->serialize($planningType, 'json', ['groups' => 'planning_api']);

        $location = $urlGenerator->generate('detailPlanning', ['id' => $planningType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonPlanningType, Response::HTTP_OK, ["Location" => $location], true);
    }




    #[Route('/api/plannings/{id}', name: "updatePlanning", methods: ['PUT'])]
    public function updatePlanning(Request $request, SerializerInterface $serializer, PlanningType $currentPlanningType,
                                   EntityManagerInterface $em, PlageHoraireRepository $plageHoraireRepository): JsonResponse
    {
        $updatedPlanning = $serializer->deserialize($request->getContent(),
            PlanningType::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentPlanningType]);
        $content = $request->toArray();
        $idPlageHoraire = $content['idplageHoraire'] ?? -1;
        $updatedPlanning->addPlageHoraire($plageHoraireRepository->find($idPlageHoraire));

        $em->persist($updatedPlanning);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }
}

