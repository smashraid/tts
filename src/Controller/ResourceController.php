<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{
    /**
     * @Route("/recursos", name="recursos")
     */
    public function index()
    {
        return $this->render('resource/index.html.twig', [
            'controller_name' => 'ResourceController',
        ]);
    }
}
