<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Entity\Employee;
use App\Form\ScheduleFormType;
use App\Form\SearchScheduleType;
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
     * @Route("/", name="schedule_index", methods={"GET","POST"})
     * @param ScheduleRepository $scheduleRepository
     * @param Request $request
     * @param LoggerInterface $logger
     * @return Response
     */
    public function index(ScheduleRepository $scheduleRepository, Request $request, LoggerInterface $logger): Response
    {
        $form = $this->createForm(SearchScheduleType::class);
        $form->handleRequest($request);
        $user = null;
        $employee = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->get('user')->getData();
            $employee = $form->get('employee')->getData();
        }
        
        $schedules = $scheduleRepository->findAllFilter($user, $employee);

        return $this->render('schedule/index.html.twig', [
            'schedules' => $schedules,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="schedule_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
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
            ['start' => 'ASC', 'timestart' => 'ASC'],            
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
