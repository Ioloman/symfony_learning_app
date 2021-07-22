<?php

namespace App\Controller;

use Knp\Bundle\MarkdownBundle\MarkdownParserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;

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

    public function getQuestion(string $question, MarkdownParserInterface $parser, CacheInterface $cache): Response
    {
        dump($cache);
        $questionText = strip_tags('`Some` **fancy** text');
        $questionMarkdown = $cache->get('markdown_'.md5($questionText), function () use ($parser, $questionText) {
            return $parser->transformMarkdown($questionText);
        });
        return $this->render('pages/questions.html.twig', [
            'question' => $question,
            'questionText' => $questionMarkdown,
            'questions' => [
                'How to build a boat? ⛵',
                'Is Math related to science? 🔬',
                '2+2=5 confirmed? 🤣',
            ],
        ]);
    }
}