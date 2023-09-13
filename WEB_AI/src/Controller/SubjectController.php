<?php

namespace App\Controller;

use App\Entity\Level;
use App\Entity\Subject;
use App\Form\SubjectType;
use App\Repository\SubjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subject')]
class SubjectController extends AbstractController
{
    #[Route('/', name: 'app_subject_index', methods: ['GET'])]
    public function index(SubjectsRepository $subjectsRepository): Response
    {
        return $this->render('subject/index.html.twig', [
            'subjects' => $subjectsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subject);
            $entityManager->flush();

            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subject/new.html.twig', [
            'subject' => $subject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subject_show', methods: ['GET'])]
    public function show(Subject $subject): Response
    {
        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
        ]);
    }
    #[Route('/{id}', name: 'app_subject_show_level', methods: ['GET'])]
    public function showlevel(Level $level): Response
    {
        $subjects = $level->getSub();

        return $this->render('subject/subjects_for_level.html.twig', [
            'level' => $level,
            'subjects' => $subjects, ]);
    }
    #[Route('/{id}/edit', name: 'app_subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subject $subject, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subject/edit.html.twig', [
            'subject' => $subject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $subject, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subject->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
    }
}
