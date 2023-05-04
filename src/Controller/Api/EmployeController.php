<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employe')]
class EmployeController extends AbstractController {

    #[Route('/{id}')]
    public function getById($id): JsonResponse
    {
        return new JsonResponse(["id" => intval($id), "personne" => 83, "matricule"=>  "00061",
    "niveau_hierarchique"=>  4,
    "n_1" =>  803,
    "cache_nom" => "AGUIG Youssef",
    "dentree" => "2017-12-01 00:00:00",
    "dsortie" => null,
    "initials"=> "AYF"]);
    }

}