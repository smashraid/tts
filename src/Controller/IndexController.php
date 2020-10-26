<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(LoggerInterface $logger)
    {        
        $logger->info('Index Controller');
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
