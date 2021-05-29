<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index()
    {
        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

       /**
     * @Route("/accueil", name="home")
     */
    public function home()
    {
        return $this->render('accueil/accueil.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route("/accueil/estimation", name="estimation")
     */
    public function estimation(Request $request)
    {   
        if( $request->isMethod("POST") ){
            //dd($request->request);
            $mCarre= $request->request->get('nbMcarre');
            //$montant=0;

            if( $mCarre <= 25){
                $montant=50;
            }
            if( ($mCarre > 25) && ($mCarre <= 50) ){
                $montant=90;
            }
            if( ($mCarre > 50) && ($mCarre <= 90) ){
                $montant=150;
            }
            if( ($mCarre > 90) && ($mCarre <= 120) ){
                $montant=200;
            }
            
        }
       
        return $this->render('accueil/accueil.html.twig',[ "montant" => $montant ]);
    }

}


