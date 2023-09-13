<?php

namespace App\Controller;

use App\Entity\Subchapter;
use App\Form\SubchapterType;
use App\Repository\SubchaptersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/subchapter')]
class SubchapterController extends AbstractController
{
    #[Route('/', name: 'app_subchapter_index', methods: ['GET'])]
    public function index(SubchaptersRepository $subchaptersRepository): Response
    {
        return $this->render('subchapter/index.html.twig', [
            'subchapters' => $subchaptersRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_subchapter_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $subchapter = new Subchapter();
        $form = $this->createForm(SubchapterType::class, $subchapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subchapter);
            $entityManager->flush();

            return $this->redirectToRoute('app_subchapter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subchapter/new.html.twig', [
            'subchapter' => $subchapter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subchapter_show', methods: ['GET'])]
    public function show(Subchapter $subchapter): Response
    {
        return $this->render('subchapter/show.html.twig', [
            'subchapter' => $subchapter,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_subchapter_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subchapter $subchapter, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubchapterType::class, $subchapter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_subchapter_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('subchapter/edit.html.twig', [
            'subchapter' => $subchapter,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_subchapter_delete', methods: ['POST'])]
    public function delete(Request $request, Subchapter $subchapter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subchapter->getId(), $request->request->get('_token'))) {
            $entityManager->remove($subchapter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_subchapter_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
