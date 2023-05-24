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

//    #[Route('/{id}')]
//    public function getEmployeById($id): JsonResponse
//    {
//        $httpClient = HttpClient::create();
//        $response = $httpClient->request('GET', 'http://761epbg2b.amandine.cloud/api/v2/personnel/'.$id, ['headers' => ['authorization' => 'bearer']]);
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
    #[Route('/{id}')]
    public function getEmployeById($id): JsonResponse
    {
        $authorization = 'Bearer ' . $this->getAuthorizationToken();
        $headers = [
            'Structure' => '1',
            'Authorization' => $authorization,
        ];

        $client = new Client();

        $response = $client->request('GET', 'http://761epbg2b.amandine.cloud/api/v2/personnel/' . $id, [
            'headers' => $headers,
           // 'content-type' => 'application/json'
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

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

    private function getAuthorizationToken(): string
    {
        $client = new Client();

        $response = $client->request('POST', 'http://761epbg2b.amandine.cloud/api/auth?_pas', []);
        $data = json_decode($response->getBody()->getContents(), true);

        $authorizationToken = $data['data']['token']['jwt'];
        return $authorizationToken;
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

//    #[Route('/{id}')]
//    public function getEmployeById($id, HttpClientInterface $httpClient): JsonResponse
//    {
//        try {
//            $authorization = 'Bearer ' . $this->getAuthorizationToken($httpClient);
//            $headers = [
//                'Structure' => '1',
//                'Authorization' => $authorization,
//            ];
//
//            $response = $httpClient->request('GET', 'http://761epbg2b.amandine.cloud/api/v2/personnel/' . $id, [
//                'headers' => $headers,
//            ]);
//
//            if ($response->getStatusCode() === 200) {
//                $data = json_decode($response->getContent(), true);
//
//                // Validation des données
//                $validatedData = $this->validateData($data);
//
//                // Utilisation de modèles de données
//                $responseData = [
//                    'id' => $validatedData['id'],
//                    'personne' => $validatedData['personne'],
//                    'matricule' => $validatedData['matricule'],
//                    'niveau_hierarchique' => $validatedData['niveau_hierarchique'],
//                    'n_1' => $validatedData['n_1'],
//                    'cache_nom' => $validatedData['cache_nom'],
//                    'dentree' => $validatedData['dentree'],
//                    'dsortie' => $validatedData['dsortie'],
//                    'initials' => $validatedData['initials'],
//                ];
//
//                return new JsonResponse($responseData);
//            } else {
//                throw new \Exception('Failed to retrieve employee data.');
//            }
//        } catch (\Exception $e) {
//            // Gestion des exceptions
//            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
//        }
//    }
//
//    private function getAuthorizationToken(HttpClientInterface $httpClient): string
//    {
//        $response = $httpClient->request('POST', 'http://761epbg2b.amandine.cloud/api/auth?_pas', []);
//
//        if ($response->getStatusCode() === 200) {
//            $data = json_decode($response->getContent(), true);
//            $authorizationToken = $data['data']['token']['jwt_refresh'];
//            return $authorizationToken;
//        } else {
//            throw new \Exception('Failed to retrieve authorization token.');
//        }
//    }
//
//    private function validateData(array $data): array
//    {
//        // Ajoutez ici vos règles de validation en utilisant Symfony Validator ou une autre bibliothèque de validation
//
//        return $data;
//    }

}