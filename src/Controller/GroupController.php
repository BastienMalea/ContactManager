<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Group;
use App\Form\GroupType;
use App\Repository\ContactRepository;
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
    public function new(Request $request, EntityManagerInterface $manager, ContactRepository $repository) : Response{

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $group = $form->getData();

            $memberIds = $form->get('members')->getData();
            foreach ($memberIds as $memberId) {
                $member = $repository -> find($memberId);
                if ($member) {
                    $group->addMember($member);
                }
            }
            $manager->persist($group);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le groupe a bien été ajouté !'
            );

            return $this->redirectToRoute('group.index');
        }

        return $this->render('pages/group/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/group/edit/{id}', name: 'group.edit', methods: ['GET', 'POST'])]
    public function update(Group $group, Request $request, EntityManagerInterface $manager) : Response{
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $group = $form->getData();
            $manager->persist($group);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le nom du groupe a bien été modifié !'
            );

            return $this->redirectToRoute('group.index');
        }

        return $this->render('/pages/group/edit.html.twig', [
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
