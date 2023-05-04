<?php
//
//namespace App\Controller;
//
//use App\Controller\Api\EmployeController;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Contracts\HttpClient\HttpClientInterface;
//
//
//
//#[Route('/planning_type/', name: 'planning_type_')]
//class PlanningTypeController extends AbstractController
//{
//    #[Route('/employe/{id}', name: 'retrieve_one',requirements: ['id'=> '\d+'], methods: ["GET"])]
//    public function showEmployePlanning(string $id): Response
//    {
//        $id = intval($id); // convertir $id en entier
//        $url = 'http://172.17.0.2/public/employe/'.$id;
//        $data = file_get_contents($url);
//        $employe = json_decode($data, true);
//
//        return $this->render('planning_type/show.html.twig', [
//            'employe' => $employe,
//        ]);
//    }
//
//
//}
