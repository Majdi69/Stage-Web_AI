<?php

namespace App\Controller;

use App\Entity\Subchapter;
use App\Entity\Subject;
use App\Entity\Chapter;
use App\Entity\Quizz;
use App\Entity\Level;
use Cassandra\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\AIController;

class HomeController extends AbstractController
{

    #[Route('/home1', name: 'app_home1')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/HomeController.php',
        ]);
    }
    #[Route('/home', name: 'app_home')]
    public function index1(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/level/{id}', name: 'show_level')]
    public function showLevel(Level $level): Response
    {
        // Assuming you have a relationship between Level and Subject entities, you can retrieve subjects like this:
        $subjects = $level->getSub();
        ;
        return $this->render('level/showlvl.html.twig', [
            'level' => $level,
            'subjects' => $subjects,
        ]);
    }

    #[Route('/level/{id}/{subjectId}', name: 'show_subject')]
    public function showSubject(Level $lvl,Subject $subject): Response
    {
        // Assuming you have a relationship between Subject and Chapter entities, you can retrieve chapters like this:
        $chapters = $subject->getChapters();
       // $lvl = $subject->getLevels();



        return $this->render('subject/show.html.twig', [
            'level'=> $lvl,
            'subject' => $subject,
            'chapters' => $chapters,
        ]);
    }


    #[Route('/level/{id}/{subjectId}/{chapterId}', name: 'show_chapter')]
    public function showChapter(Chapter $chapter,Subject $subj,Level $lvl): Response
    {
        // Assuming you have a relationship between Chapter and Subchapter entities, you can retrieve subchapters like this:
        $subchapters = $chapter->getSubchapters();


        return $this->render('chapter/show.html.twig', [
            'level'=> $lvl,
            'subject' => $subj,
            'chapter' => $chapter,
            'subchapters' => $subchapters,
        ]);
    }


    #[Route('/level/{id}/{subjectId}/{chapterId}/{subchapterId}', name: 'show_subchapter')]
    public function showSubchapter(Chapter $chapter,Subject $subj,Level $lvl, Subchapter $subchapter): Response
    {
        return $this->render('subchapter/show.html.twig', [
            'level'=> $lvl,
            'subject' => $subj,
            'chapter' => $chapter,
            'subchapter' => $subchapter,
        ]);
    }

    #[Route('/level/{id}/{subjectId}/{chapterId}/{subchapterId}/GenerateQuiz', name: 'generate_quiz', methods: ['POST'])]
    public function generateText(Chapter $chapter,Subject $subj,Level $lvl, Subchapter $subchapter, Request $request)
    {
        
    $form = $this->createForm(PromptFormType::class);
    $form->handleRequest($request);

    $generatedText = null;

    if ($form->isSubmitted() && $form->isValid()) {
        
        $prompt = 'pouvez vous generer un quiz pour niveau 1ere lycee, matiere :' . $Subject . ', chapitre :' . $chapter . 'et sous-chapitre :' . $subchapter;


        $apiKey = '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';

   echo generateTextWithOpenAI($prompt, $apiKey);
        $generatedText = $this->generateTextWithOpenAI($prompt, $apiKey);
    }

    return $this->render('subchapter/show.html.twig', [
        
        'generatedText' => $generatedText,
    ]);
    }
}
