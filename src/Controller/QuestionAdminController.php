<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_ADMIN_QUESTION")
 */
class QuestionAdminController extends AbstractController
{
    public function create(): Response
    {
        return $this->render('question_admin/create.html.twig', []);
    }
}
