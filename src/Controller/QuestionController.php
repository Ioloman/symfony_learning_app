<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Main page controller
     *
     */
    public function getHomepage(): Response
    {
        $repository = $this->entityManager->getRepository(Question::class);
        $questions = $repository->findAllAskedFollowedByNewest();
        return $this->render('pages/main.html.twig', ['questions' => $questions]);
    }

    public function getQuestion(Question $question): Response
    {
        return $this->render('pages/questions.html.twig', [
            'question' => $question,
        ]);
    }

    public function newQuestion(): Response
    {
        return new Response('Creation form is yet to be added');
    }

    public function questionVote(Question $question, Request $request): Response
    {
        $direction = $request->request->get('direction');

        $question->vote($direction);

        $this->entityManager->flush();

        return $this->redirectToRoute('question', ["slug" => $question->getSlug()]);
    }
}