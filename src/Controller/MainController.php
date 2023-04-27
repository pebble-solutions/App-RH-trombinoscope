<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatType;
use App\Repository\EtatRepository;
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

    #[Route('/add', name: 'addEtat')]
    public function addEtat(EtatRepository $etatRepository, Request $request): Response
    {
        $etat = new Etat();
        $etatForm = $this->createForm(EtatType::class, $etat);
        $etatForm->handleRequest($request);

        //si soumis rentre en BDD
        if($etatForm->isSubmitted()){
            $etatRepository->save($etat, true);
            //Affiche message si bien enristré en BDD
            $this->addFlash('succes', "Etat Ajouté !");
          //  return $this->redirectToRoute('main/index.html.twig');
        }

        return $this->render('main/add.html.twig',['etat' => $etat,
            'etatForm' => $etatForm->createView()
        ]);

    }

}
