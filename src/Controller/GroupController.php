<?php

namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends AbstractController
{
    #[Route('/group', name: 'group.index', methods: ['GET'])]
    public function index(GroupRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $groups = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('pages/group/index.html.twig', [
            'groups' => $groups,
        ]);
    }

    #[Route('/group/new', name: 'group.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response{

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $group = $form->getData();
            $manager->persist($group);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le groupe a bien été ajouté !'
            );

            return $this->redirectToRoute('contact.index');
        }

        return $this->render('pages/group/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/group/delete/{id}', name: 'group.delete', methods: ['GET'])]
    public function delete(Group $group, EntityManagerInterface $manager) : Response
    {
        $manager ->remove($group);
        $manager -> flush();

        $this->addFlash(
            'success',
            'Le Groupe a bien été supprimé !'
        );

        return $this->redirectToRoute('group.index');
    }
}
