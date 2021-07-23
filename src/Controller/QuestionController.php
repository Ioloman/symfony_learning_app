<?php

namespace App\Controller;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('pages/main.html.twig', ['hello' => 'Hellow from controller']);
    }

    public function getQuestion(string $slug): Response
    {
        $repository = $this->entityManager->getRepository(Question::class);
        /** @var Question|null $question */
        $question = $repository->findOneBySlug($slug);
        // or $repository->findOneBy(['slug' => $slug]);

        if (!$question) {
            throw $this->createNotFoundException('The requested question is Not Found 😥');
        }

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
            )->setSlug(sprintf("where-do-i-get-laces-%d", rand(100, 999)))
            ->setAskedAt(new \DateTimeImmutable('-7 days'));

        $this->entityManager->persist($question);
        $this->entityManager->flush();

        return new Response(sprintf(
            "ID is %d, slug is %s",
            $question->getId(),
            $question->getSlug()
        ));
    }
}