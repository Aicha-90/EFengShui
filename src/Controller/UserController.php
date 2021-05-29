<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Diagnostic;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\UserSubsFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Repository\DiagnosticRepository;
use App\Repository\CritereRepository;
use Symfony\Component\Form\FormView;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\DependencyInjection\ExpressionLanguageProvider;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Swift_Mailer;



class UserController extends AbstractController
{
  
  /**
     * @Route("/inscription", name="user_subs")
      * @Security("not is_granted('ROLE_USER')")
     */
public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(UserSubsFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('user/index.html.twig', [
            'UserSubsForm' => $form->createView(),
          
        ]);
    }

    /**
     * @Route("/user", name="compte_user")
     * @Security("is_granted('ROLE_USER')")
     */
    public function infos_user(UserRepository $ur, DiagnosticRepository $dr, CritereRepository $cr)
    {
    $user = $ur->findAll();
    $diagnostic = $dr->findAll();
    $critere = $cr->findAll();

        return $this->render('user/compte_user.html.twig', [ "user" => $ur,"diagnostic" => $dr, "critere" => $cr ]);
    }

   /**
 * @Route("/user/modifier/{id}", name="user_modifier")
    * @Security("is_granted('ROLE_USER')")
 */
public function modifier(UserRepository $ur, Request $request, EMI $em, int $id )
{
    $bouton = "update";
    $userAmodifier = $ur->find($id);

    // Récupération des données envoyées par le formulaire
    if($request->isMethod("POST")){ 
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $mdp = $request->request->get('password');

        // Création d'un objet Record avec les données récupérées
        $userAmodifier->setNom($nom);
        $userAmodifier->setPrenom($prenom);
        $userAmodifier->setPassword($mdp);
        
        // Enregistrement en BDD
        $em->persist($userAmodifier);
        $em->flush();

        return $this->redirectToRoute("compte_user");

    }
    return $this->render('user/modif_user.html.twig', ["user" => $userAmodifier, "bouton" => $bouton]);
} 


    /**
     * @Route("/user/supprimer/{id}", name="user_supprimer")
     * @Security("is_granted('ROLE_USER')")
     */

    public function supprimer(UserRepository $ur, Request $request, EMI $em, int $id)
    {

        $userAsupprimer = $ur->find($id);

            // Enregistrement en BDD
            $em->remove($userAsupprimer);
            $em->flush();


            // Ajout de message Alert
            $this->addFlash(
                'info',
                'Etes vous sur de bien vouloir supprimer votre compte?'
            );        
        return $this->render('user/supprimer_user.html.twig', ["user" => $userAsupprimer,]);
    }


    /**
    * @Route("/admin/gestion", name="gestion")   
    * @IsGranted("ROLE_ADMIN") 
    */
    public function compte_admin(UserRepository $ur, DiagnosticRepository $dg, CritereRepository $cr)
    {
        $clients=$ur->findAll();
        $diagnostics=$dg->findAll();
        $demandes=$cr->findAll();
        
        return $this->render("user/compte_admin.html.twig",["listeclient" => $clients, "listediag" => $diagnostics, "listedemande" => $demandes] );
    }
        
    /**
     * @Route("/admin/user/ajouter", name="user_add")
     * @IsGranted("ROLE_ADMIN")
     */
    public function add(UserRepository $ur, EMI $em, Request $request)
    {
        $bouton = "add";

        if($request->isMethod("POST")){ 
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            $mdp = $request->request->get('password');
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            $user = new User; 
            $user->setEmail($nom);
            $user->setPassword($mdp);

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("gestion");

        }else{
            return $this->render('user/compte_admin.html.twig', ["bouton" => $bouton]); 
        }
    }

    /**
     * @Route("/admin/user/modifier/{id}", name="user_update")
     * @IsGranted("ROLE_ADMIN")
     */
    public function update(UserRepository $ur, Request $request, EMI $em, int $id)
    {
        $bouton = "update";
        $userAmodifier = $ur->find($id);

        if($request->isMethod("POST")){ 
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $email = $request->request->get('email');
            // trim supprime les espaces au début et à la fin d'une chaîne de caractères
            $mdp = trim($request->request->get('password'));
            if($mdp){
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);
                $userAmodifier->setPassword($mdp);
            }
            
            $userAmodifier->setNom($nom);
            $userAmodifier->setPrenom($prenom);
            $userAmodifier->setEmail($email);

            $em->persist($userAmodifier);
            $em->flush();

            return $this->redirectToRoute("gestion");

        }
        return $this->render('user/formulaire.html.twig', ["user" => $userAmodifier, "bouton" => $bouton]); 

    }


    /**
     * @Route("/admin/user/supprimer/{id}", name="user_delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(UserRepository $ur, Request $request,EMI $em, int $id)
    {
        $bouton = "delete";
        $userAsupprimer = $ur->find($id);

        if ($request->isMethod("POST")){
            $em->remove($userAsupprimer);
            $em->flush();
            return $this->redirectToRoute("gestion");
        }
        return $this->render('user/formulaire.html.twig', ["user" => $userAsupprimer, "bouton" => $bouton]);
    } 
    /**
     * @Route("/forgotten_password", name="app_forgotten_password")
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $encoder, Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, EMI $em): Response
    {

        if ($request->isMethod('POST')) {

            $email = $request->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            /* @var $user User */
            $token = $tokenGenerator->generateToken();

            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
            }
            else{
                $user->setResetToken($token);
                $em->flush();
        

            $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('kathleen.stassart@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    " Voici le lien pour générer votre nouveau mot de passe : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success', 'Mail envoyé');
            return $this->redirectToRoute('accueil');
        }
    }

        return $this->render('security/forgotten_password.html.twig');
    }

     /**
     * @Route("/reset_password/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, EMI $em)
    {

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);;
            /* @var $user User */

            if ($user === null) {
                $this->addFlash('danger', 'Le chemin est inconnu');
            }

            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour');
            return $this->redirectToRoute('accueil');
        }else {

            return $this->render('security/reset_password.html.twig', ['token' => $token]);
        }

    }
}
