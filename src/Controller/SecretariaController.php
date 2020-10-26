<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecretariaController extends AbstractController
{
    /**
     * @Route("/secretaria", name="secretaria")
     */
    public function index()
    {
        return $this->render('secretaria/index.html.twig', [
            'controller_name' => 'SecretariaController',
        ]);
    }
}
