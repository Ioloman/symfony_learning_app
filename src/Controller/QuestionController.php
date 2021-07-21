<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    /**
     * Main page controller
     *
     */
    public function getHomepage(): Response
    {
        return $this->render('pages/main.html.twig', ['hello' => 'Hellow from controller']);
    }

    public function getQuestion(string $question): Response
    {
        return $this->render('pages/questions.html.twig', [
            'question' => $question,
            'questions' => [
                'How to build a boat? ⛵',
                'Is Math related to science? 🔬',
                '2+2=5 confirmed? 🤣',
            ],
        ]);
    }
}