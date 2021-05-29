<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class FengshuiController extends AbstractController

{
    /**
     * @Route("/fengshui", name="fengshui")
     */
    public function fengshui()
    {
        return $this->render('fengshui/fengshui.html.twig', [

            'controller_name' => 'FengshuiController',
        ]);
    }

}
