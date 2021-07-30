<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuestionAdminController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN_QUESTION")
     */
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Question $question */
            $question = $form->getData();
            
            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question is submitted!');

            return $this->redirectToRoute('listQuestionAdmin');
        }

        return $this->render('question_admin/create.html.twig', [
            'questionForm' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN_QUESTION")
     */
    public function list(QuestionRepository $repository): Response
    {
        return $this->render('question_admin/list.html.twig', [
            'questions' => $repository->findAll(),
        ]);
    }

    /**
     * @IsGranted("MANAGE", subject="question")
     */
    public function edit(Question $question, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionFormType::class, $question);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question is Updated!');

            return $this->redirectToRoute('editQuestionAdmin', ['id' => $question->getId()]);
        }

        return $this->render('question_admin/edit.html.twig', [
            'questionForm' => $form->createView(),
        ]);
    }
}
