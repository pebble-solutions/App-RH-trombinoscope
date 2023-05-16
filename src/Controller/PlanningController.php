<?php

namespace App\Controller;

use App\Entity\PlageHoraire;
use App\Entity\PlanningType;
use App\Repository\EtatRepository;
use App\Repository\PlageHoraireRepository;
use App\Repository\PlanningTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PlanningController extends AbstractController
{
    #[Route('/api/plannings', name: 'app_planning', methods: ['GET'])]
    public function getAllPlannings(PlanningTypeRepository $planningTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $planningList = $planningTypeRepository->findAll();
        $jsonPlanningList = $serializer->serialize($planningList, 'json', ['groups' => 'planning_api']);
        return new JsonResponse($jsonPlanningList, Response::HTTP_OK, [], true);
    }


    #[Route('/api/plannings/{id}', name: 'detailPlanning', methods: ['GET'])]
    public function getDetailPlanning(PlanningType $planningType, SerializerInterface $serializer): JsonResponse
    {
        $jsonPlanningType = $serializer->serialize($planningType, 'json', ['groups' => 'planning_api']);
        return new JsonResponse($jsonPlanningType, Response::HTTP_OK, ['accept' => 'json'], true);
    }


    #[Route('/api/plannings/{id}', name: 'deletePlanning', methods: ['DELETE'])]
    public function deleteBook(PlanningType $planningType, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($planningType);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/plannings', name: "createPlanning", methods: ['POST'])]
    public function createPlanning(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
                                   UrlGeneratorInterface $urlGenerator, PlanningTypeRepository $planningTypeRepository,
                                   PlageHoraireRepository $plageHoraireRepository, EtatRepository $etatRepository): JsonResponse
    {

        $planningType = $serializer->deserialize($request->getContent(), PlanningType::class, 'json');

//        dd($planningType);
        // Récupération de l'ensemble des données envoyées sous forme de tableau
        $content = $request->toArray();

        // Récupération de l'idPlageHoraire. S'il n'est pas défini, alors on met -1 par défaut.
        $idPlageHoraire = $content['idplageHoraire'] ?? -1;

        // On cherche la plage horaire qui correspond et on l'assigne au planningType.
        // Si "find" ne trouve pas la plage horaire, alors null sera retourné.
        $planningType->addPlageHoraire($plageHoraireRepository->find($idPlageHoraire));

        $em->persist($planningType);
        $em->flush();

        $jsonPlanningType = $serializer->serialize($planningType, 'json', ['groups' => 'planning_api']);

        $location = $urlGenerator->generate('detailPlanning', ['id' => $planningType->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonPlanningType, Response::HTTP_CREATED, ["Location" => $location], true);
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