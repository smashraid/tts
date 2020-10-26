<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class InformesController extends AbstractController
{
    /**
     * @Route("/informes", name="informes")
     */
    public function index(LoggerInterface $logger)
    {
        $logger->info('Informes Controller');
        return $this->render('informes/index.html.twig', [
            'controller_name' => 'InformesController',
        ]);
    }
}
