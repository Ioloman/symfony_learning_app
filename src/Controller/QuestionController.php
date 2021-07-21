<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class QuestionController
{
    /**
     * Main page controller
     *
     */
    public function getHomepage()
    {
        return new Response('Hello Symfony');
    }
}