<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends AbstractController
{

    public function index(): Response
    {
        return $this->render('account/index.html.twig', []);
    }

    public function accountApi(): Response
    {
        return $this->json($this->getUser(), 200, [], ['groups' => ['main']]);
    }
}
