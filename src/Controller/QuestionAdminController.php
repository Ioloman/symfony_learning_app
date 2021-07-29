<?php

namespace App\Controller;

use App\Entity\Question;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class QuestionAdminController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN_QUESTION")
     */
    public function create(): Response
    {
        return $this->render('question_admin/create.html.twig', []);
    }

    /**
     * @IsGranted("MANAGE", subject="question")
     */
    public function edit(Question $question): Response
    {
        return $this->json('good');
    }
}
