<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentAdminController extends AbstractController
{
    public function index(CommentRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repository->getAllWithSearchQueryBuilder($request->query->get('q')),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('comment_admin/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
