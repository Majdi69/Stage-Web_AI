<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use App\Form\PromptFormType;

class AIController extends AbstractController
{
   

    private function generateTextWithOpenAI($prompt, $apiKey)
    {
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
}
