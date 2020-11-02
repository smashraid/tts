<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Employee;
use App\Entity\Schedule;
use App\Form\SearchUserFormType;
use App\Form\ScheduleFormType;
use Psr\Log\LoggerInterface;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $lastname = null;

        $user = new User();
        $form = $this->createForm(SearchUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lastname = $form->get('lastname')->getData();            
        }

        $users = $repository->findAllBasic($lastname);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="schedule", requirements={"id"="\d+"}))
     */
    public function schedule(User $user, Request $request, LoggerInterface $logger)
    {
        $logger->info('Admin Controller');        
        $entityManager = $this->getDoctrine()->getManager();        

        $schedule = new Schedule();
        $schedule->setUser($user);
        $logger->info(json_encode($schedule));

        $form = $this->createForm(ScheduleFormType::class, $schedule, [
            'user_id' => 1
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schedule = $form->getData();
            $entityManager->persist($schedule);
            $entityManager->flush();
            return $this->redirectToRoute('admin');
        }

        return $this->render('schedule/new.html.twig', [
            'controller_name' => 'AdminController',
            'schedule' => $schedule,
            'form' => $form->createView(),
        ]);
    }
}
