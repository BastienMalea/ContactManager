<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/', name: 'contact.index' , methods: ['GET'])]
    public function index(ContactRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $contacts = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/new', name: 'contact.new', methods: ['GET', 'POST'])]
    public function new() : Response
    {
        return $this->render('pages/contact/new.html.twig');
    }
}
