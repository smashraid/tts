<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Employee;
use App\Form\ScheduleFormType;
use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

/** 
 * @Route("/schedule") * 
 */
class ScheduleController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="schedule_index", methods={"GET"})
     */
    public function index(ScheduleRepository $scheduleRepository): Response
    {
        return $this->render('schedule/index.html.twig', [
            'schedules' => $scheduleRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="schedule_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $schedule = new Schedule();        
        $form = $this->createForm(ScheduleFormType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($schedule);
            $entityManager->flush();

            return $this->redirectToRoute('schedule_index');
        }

        return $this->render('schedule/new.html.twig', [
            'schedule' => $schedule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/list", name="schedule_show", methods={"GET"})
     */
    public function show(LoggerInterface $logger): Response
    {
        $logger->info('Schedule Controller');
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Schedule::class);
        $user = $this->getUser();
        $schedules = $repository->findBy(
            ['user' => $user->getId()],
            ['timestart' => 'ASC']
        );        

        return $this->render('schedule/show.html.twig', [
            'schedules' => $schedules            
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/edit", name="schedule_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Schedule $schedule, LoggerInterface $logger): Response
    {
        $form = $this->createForm(ScheduleFormType::class, $schedule);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('schedule_index');
        }

        return $this->render('schedule/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}", name="schedule_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Schedule $schedule): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schedule->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($schedule);
            $entityManager->flush();
        }

        return $this->redirectToRoute('schedule_index');
    }
}
