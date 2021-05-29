<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CritereRepository;
use App\Repository\DiagnosticRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Diagnostic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

class DiagnosticController extends AbstractController
{

    /**
     * @Route("/diagnostic", name="diagnostic")
     */
    public function index(){
        return $this->render('diagnostic/index.html.twig', [
            'controller_name' => 'DiagnosticController',
        ]);
    }

     /**
     * @Route("/admin/diagnostic/ajouter/{id}", name="diagnostic_ajouter")
     * @IsGranted("ROLE_ADMIN")  
     */
    public function add(Request $rq, EntityManagerInterface $em,CritereRepository $critereRepo, int $id){

        $cr=$critereRepo->find($id);
        $surface=$cr->getNbMCarre();

        //Ici on determine le prix du diagnostic en fonction de la surface
        if( $surface <= 25){
            $prix=50;
        }
        if( ($surface > 25) && ($surface <= 50) ){
            $prix=90;
        }
        if( ($surface > 50) && ($surface <= 90) ){
            $prix=150;
        }
        if( ($surface > 90) && ($surface <= 120) ){
            $prix=200;
        }
        
        //ici on gère le chargement du pdf contenant le diagnostic
       // if( $rq->isMethod("GET") ){
            //$pdf = $rq->files->get('pdfExpertise');
            dd($rq->files->get('pdfExpertise'));
            $destination = $this->getParameter("dossier_images");
       // }
       // else{
            $pdf="diagnostic.pdf";
       // }

        //Creation de l'objet diagnostic
        $diagnostic = new Diagnostic; 

        $diagnostic->setDate(new \DateTime('now'));
        $diagnostic->setPrix($prix);
        $diagnostic->setStatutPaiement(true);
        $diagnostic->setStatutExpertise(true);
        $diagnostic->setExpertise($pdf);
        $diagnostic->setCritere($cr);

        //Insertion dans la table Diagnostic
        $em->persist($diagnostic);
        $em->flush();

        $this->addFlash("success", "Votre diagnostic a bien été ajouté sur le commpte du client");

        return $this->redirectToRoute("gestion");
        
    }



  
}
