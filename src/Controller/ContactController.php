<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Swift_Mailer;
use App\Form\ContactType;
use Symfony\Component\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, Swift_Mailer $mailer, TranslatorInterface $translator)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            //dd($contact);
            
            // Ici nous enverrons l'e-mail
            $message = (new \Swift_Message('Nouveau contact'))
            // On attribue l'expéditeur
            ->setFrom($contact['email'])

            // On attribue le destinataire
            ->setTo('maitre.fengshui.n1@gmail.com')

            // On crée le texte avec la vue
            ->setBody(
                $this->render(
                    'contact/formContact.html.twig', compact('contact')
                ),
                'text/html'
            );

            $mailer->send($message);

            $msg = $translator->trans('Your message has been sent, we will answer you as soon as possible.');
        
            //Confirmation de l'envoi
            $this->addFlash('message', $msg); // Permet un message flash de renvoi
        }
        
        return $this->render('contact/formContact.html.twig',['contactForm' => $form->createView()]);

    }


}
