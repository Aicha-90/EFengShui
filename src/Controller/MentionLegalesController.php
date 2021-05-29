<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MentionLegalesController extends AbstractController
{
    /**
     * @Route("/mentions/legales", name="mentions_legales")
     */
    public function index()
    {
        return $this->render('mention_legales/mentionslegales.html.twig', [
            'controller_name' => 'MentionLegalesController',
        ]);
    }
}
