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
use Symfony\Component\HttpFoundation\Request;
use App\Controller\AIController;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Client;


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

    private function generateTextWithOpenAI($prompt, $apiKey)
    {
        $quizz = new Quizz();
        // Create a Guzzle client
        $httpClient = new Client();

        // Define the OpenAI API endpoint URL
        $openaiApiUrl = 'https://api.openai.com/v1/engines/davinci/completions';

        // Prepare the request data
        $requestData = [
            'prompt' => $prompt,
            'max_tokens' => 50, // Adjust as needed
        ];

        // Send a POST request to the OpenAI API
        try {
            $response = $httpClient->post($openaiApiUrl, [
                'json' => $requestData,
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
            ]);

            // Check if the request was successful (status code 200)
            if ($response->getStatusCode() === 200) {
                // Parse and return the generated text from the response
                $responseData = json_decode($response->getBody()->getContents(), true);
                if (isset($responseData['choices'][0]['text'])) {
                    return $responseData['choices'][0]['text'];
                } else {
                    return 'Text generation failed.';
                }
            } else {
                return 'OpenAI API request failed.';
            }
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    #[Route('/level/{id}/{subjectId}/{chapterId}/{subchapterId}/GenerateQuiz', name: 'generate_quiz', methods: ['GET'])]
    public function generateText(Level $lvl,Subject $subj,Chapter $chapter, Subchapter $subchapter, Request $request)
    {
        
    $generatedText = 'null';

        
        $prompt = 'Pouvez-vous générer un quiz pour la classe de 1ère année du lycée, matière : '. $subj->getTitle() .' chapitre :' . $chapter->getTitle().', et sous-chapitre :' .$subchapter->getTitle().' ?';


        $apiKey = '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';

   
        $generatedText = $this->generateTextWithOpenAI($prompt, $apiKey);
    

    return $this->render('subchapter/show.html.twig', [
        
        'generatedText' => $generatedText,
    ]);
    }
}
