<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CritereRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ConnexionType;
use App\Form\CriteresType;
use App\Form\Criteres2Type;
use App\Entity\User;
use App\Entity\Critere;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CriteresController extends AbstractController
{
    private $session;
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

   /**
     * @Route("/criteres/ajouter", name="criteres_ajouter")
     */

    public function add(CritereRepository $critereRepo, Request $rq, EntityManagerInterface $em, UserRepository $ur, AuthenticationUtils $authenticationUtils)
   {
        $this->session->set('diagnostic_demande', 'diagnostic_demande');
        //Name et Value
        $diagnosticDemande = $this->session->get('diagnostic_demande');

        $user = new User();
        $formConnexion = $this->createForm(ConnexionType::class);
        //$demande = new Criteres();

        $formDemande = $this->createForm(CriteresType::class);
        //Il crée le formulaire à partir du LivreType::class, d'où l'argument

        $formDemande->handleRequest($rq);
        //Permet de lier la requête HTTP à mon objet Formulaire. Voir si ça a été soumis?
        if ($formDemande->isSubmitted() && $formDemande->isValid()) {
            //Si mon formulaire a été soumis
            
                //Je teste s'il est valide
                //S'il est valide je crée $nouvelleDemande
                $nouvelleDemande = $formDemande->getData();
                //getData va permettre de créer l'objet en récupérant les données je récupère la valeur du paramètre global "dossier_images" 
                // pour définir dans quel dossier va être enregistré l'image téléchargée

                $destination = $this->getParameter("dossier_images");
                //Je mets les informations de la photo téléchargée dans la variable phototelecharger et s'il y a bien une photo téléchargée
                if(($planTelecharge = $formDemande["plan_lieu"]->getData()) && ($photoTelechargee = $formDemande["photo_lieu"]->getData())){
                    //je récupère le nom de la photo 
                    $nomPlan = pathinfo($planTelecharge->getClientOriginalName(), PATHINFO_FILENAME);
                    $nomPhoto = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                    //Je supprime les éventuels espaces début fin
                    $nouveauNomPlan = trim($nomPlan);
                    $nouveauNomPhoto = trim($nomPhoto);
                    //Je remplace les espaces
                    $nouveauNomPlan = str_replace(" ", "_", $nouveauNomPlan);
                    $nouveauNomPlan .= "-" . uniqid() . "." . $planTelecharge->guessExtension();
                    $nouveauNomPhoto = str_replace(" ", "_", $nouveauNomPhoto);
                    $nouveauNomPhoto .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();
                    $planTelecharge->move($destination, $nouveauNomPlan);
                    $photoTelechargee->move($destination, $nouveauNomPhoto);
                    $nouvelleDemande->setPlanLieu($nouveauNomPlan);         
                    $nouvelleDemande->setPhotoLieu($nouveauNomPhoto);          
                }
                
                $nouvelleDemande->setUser($this->getUser());     
                $em->persist($nouvelleDemande);
                //On récupère
                $em->flush();
                //On lance
                $translator = "";
                $msg = $translator->trans('Your request has been registered.');
                $this->addFlash("success", $msg);

                //Permet d'envoyer des messages. Premier le type "error" => "danger", "success",... et ensuite on met le message
                // return $this->redirectToRoute("accueil");
                //Suite à tout ça, on redirige en mettant le name de la route où l'on souhaite rediriger               
                 /*else {

                $msg = $translator->trans("The form is not valid.");
                $this->addFlash("danger", $msg);

                //Dans le cas où le formulaire n'est pas. "danger" et "succès" sont des classes à bootstrap.
            }*/
        }

        $formDemande = $formDemande->createView();      
        $formConnexion = $formConnexion->createView();
        return $this->render('critere/formulaire.html.twig', compact('formDemande', 'formConnexion'));
    }
}