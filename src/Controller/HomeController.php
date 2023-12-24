<?php

namespace App\Controller;

use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home.index', methods: ['GET'])]
    public function index(ContactRepository $repository): Response
    {
        $contacts = $repository->findAll();

        return $this->render('pages/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
