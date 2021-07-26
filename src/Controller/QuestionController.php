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
    private $entityManager;

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
            'questions' => [
                'How to build a boat? ⛵',
                'Is Math related to science? 🔬',
                '2+2=5 confirmed? 🤣',
            ],
        ]);
    }

    public function newQuestion(): Response
    {
        $question = new Question();
        $question
            ->setName('Where to get a new rubber laces??')
            ->setQuestion(<<<EOF
Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci assumenda blanditiis cumque delectus eaque

eligendi eum explicabo hic, iure officia quasi ratione rem sunt tempore veritatis vero vitae, voluptate

voluptatum!

`print(f'python is {cool}')`
EOF
            )->setSlug(sprintf("where-do-i-get-laces-%d", rand(100, 999)));

        if (rand(1, 10) > 5) {
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 1000))));
        }
        $this->entityManager->persist($question);
        $this->entityManager->flush();

        return new Response(sprintf(
            "ID is %d, slug is %s",
            $question->getId(),
            $question->getSlug()
        ));
    }

    public function questionVote(Question $question, Request $request): Response
    {
        $direction = $request->request->get('direction');

        $question->vote($direction);

        $this->entityManager->flush();

        return $this->redirectToRoute('question', ["slug" => $question->getSlug()]);
    }
}