<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use App\Service\UploadHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionAdminController extends AbstractController
{
    private UploadHelper $uploadHelper;

    public function __construct(UploadHelper $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
    }

    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Question $question */
            $question = $form->getData();

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('imageFile')->getData();

            if ($uploadedFile) {
                $newFilename = $this->uploadHelper->uploadQuestionImage($uploadedFile);

                $question->setImageFilename($newFilename);
            }
            
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
        $form = $this->createForm(QuestionFormType::class, $question, ['include_asked_at' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('imageFile')->getData();

            if ($uploadedFile) {
                $newFilename = $this->uploadHelper->uploadQuestionImage($uploadedFile);

                $question->setImageFilename($newFilename);
            }

            $entityManager->persist($question);
            $entityManager->flush();

            $this->addFlash('success', 'Question is Updated!');

            return $this->redirectToRoute('editQuestionAdmin', ['id' => $question->getId()]);
        }

        return $this->render('question_admin/edit.html.twig', [
            'questionForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/upload", name="upload")
     */
    public function temporaryFileUploadEndpoint(Request $request)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('image');
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        dd($uploadedFile->move(
            $destination,
            $newFilename
        ));
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function getSpecificTopicChoices(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN_QUESTION') && $this->getUser()->getQuestions()->isEmpty()) {
            throw $this->createAccessDeniedException();
        }

        $question = new Question();
        $question->setTopic($request->query->get('topic'));
        $form = $this->createForm(QuestionFormType::class, $question);

        if (!$form->has('specificTopic')) {
            return new Response(null, 204);
        }

        return $this->render('question_admin/_specific_topic.html.twig', [
            'questionForm' => $form->createView()
        ]);
    }
}
