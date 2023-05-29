<?php

namespace App\Controller\Api;

use GuzzleHttp\Client;
use http\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/employe')]
class EmployeController extends AbstractController
{
//
//    #[Route('/{id}')]
//    public function getById($id): JsonResponse
//    {
//        return new JsonResponse(["id" => intval($id), "personne" => 83, "matricule" => "00061",
//            "niveau_hierarchique" => 4,
//            "n_1" => 803,
//            "cache_nom" => "AGUIG Youssef",
//            "dentree" => "2017-12-01 00:00:00",
//            "dsortie" => null,
//            "initials" => "AYF"]);
//    }

    #[Route('/{id}')]
    public function getEmployeById($id): JsonResponse
    {
        $httpClient = HttpClient::create();
        $response = $httpClient->request('GET', 'http://761epbg2b.amandine.cloud/api/v2/personnel/'.$id, ['headers' => ['authorization' => 'bearer']]);
        $data = json_decode($response->getContent(), true);

        return new JsonResponse([
            'id' => $data['id'],
            'personne' => $data['personne'],
            'matricule' => $data['matricule'],
            'niveau_hierarchique' => $data['niveau_hierarchique'],
            'n_1' => $data['n_1'],
            'cache_nom' => $data['cache_nom'],
            'dentree' => $data['dentree'],
            'dsortie' => $data['dsortie'],
            'initials' => $data['initials'],
        ]);
    }

//    #[Route('/{id}')]
//    public function getEmployeById($id, HttpClientInterface $httpClient): JsonResponse
//    {
//        $authorization = 'Bearer ' . $this->getAuthorizationToken($httpClient);
//        $headers = [
//            'Structure' => '1',
//            'Authorization' => $authorization,
//        ];
//
//        $response = $httpClient->request('GET', 'http://761epbg2b.amandine.cloud/api/v2/personnel/' . $id, [
//            'headers' => $headers,
//        ]);
//
//        $data = json_decode($response->getContent(), true);
//
//        return new JsonResponse([
//            'id' => $data['id'],
//            'personne' => $data['personne'],
//            'matricule' => $data['matricule'],
//            'niveau_hierarchique' => $data['niveau_hierarchique'],
//            'n_1' => $data['n_1'],
//            'cache_nom' => $data['cache_nom'],
//            'dentree' => $data['dentree'],
//            'dsortie' => $data['dsortie'],
//            'initials' => $data['initials'],
//        ]);
//    }
//
//    private function getAuthorizationToken(HttpClientInterface $httpClient): string
//    {
//        $response = $httpClient->request('POST', 'http://761epbg2b.amandine.cloud/api/auth?_pas', []);
//        $data = json_decode($response->getContent(), true);
//
//        $authorizationToken = $data['data']['token']['jwt'];
//        return $authorizationToken;
//    }



}