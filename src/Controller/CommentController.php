<?php


namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    public function commentVote(int $id, string $direction, LoggerInterface $logger): Response
    {
        // TODO: add database interaction
        if ($direction == 'down') {
            $logger->info('voting down');
        } else {
            $logger->info('voting up');
        }
        return $this->json(['votes' => ($direction == 'down' ? rand(0, 5) : rand(6, 10))]);
    }
}