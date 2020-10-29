<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Employee;

class ProductController extends AbstractController
{
    /**
     * @Route("/servicios", name="servicios")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/servicios/{slug}", name="servicios_show")
     */
    public function show(string $slug)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Employee::class);
        $employees = $repository->findBy(
            ['type' => $slug]
        );

        return $this->render('product/show.html.twig', [
            'controller_name' => 'DetailController',
            'employees' => $employees,
        ]);
    }
}
