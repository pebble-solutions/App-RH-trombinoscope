<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\PlageHoraire;
use App\Form\EtatType;
use App\Form\PlageHoraireType;
use App\Repository\EtatRepository;
use App\Repository\PlageHoraireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    #[Route('/add', name: 'addPlageHoraire')]
    public function addPlageHoraire(PlageHoraireRepository $plageHoraireRepository,Request $request): Response
    {

        $plageHoraire = new PlageHoraire();
        $plageHoraireForm = $this->createForm(PlageHoraireType::class, $plageHoraire);
        $plageHoraireForm->handleRequest($request);


        //si soumis rentre en BDD
        if($plageHoraireForm->isSubmitted()){
            $plageHoraireRepository->save($plageHoraire, true);

            //Affiche message si bien enristré en BDD
            $this->addFlash('succes', "Plage Horaire Ajoutée !");
          //  return $this->redirectToRoute('main/index.html.twig');
        }

        return $this->render('main/add.html.twig',['plageHoraire' => $plageHoraire,
            'plageHoraireForm' => $plageHoraireForm->createView()
        ]);

    }
//    #[Route('/add', name: 'addEtat')]
//    public function addEtat(EtatRepository $etatRepository, Request $request): Response
//    {
//        $etat = new Etat();
//        $etatForm = $this->createForm(EtatType::class, $etat);
//        $etatForm->handleRequest($request);
//
//        //si soumis rentre en BDD
//        if($etatForm->isSubmitted()){
//            $etatRepository->save($etat, true);
//            //Affiche message si bien enristré en BDD
//            $this->addFlash('succes', "Etat Ajouté !");
//            //  return $this->redirectToRoute('main/index.html.twig');
//        }
//
//        return $this->render('main/add.html.twig',['etat' => $etat,
//            'etatForm' => $etatForm->createView()
//        ]);
//
//    }
}
