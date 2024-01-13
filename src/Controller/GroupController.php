<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Group;
use App\Form\GroupType;
use App\Form\SearchType;
use App\Model\SearchData;
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
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $searchData->page = $request->query->getInt('page', 1);
            $dataFound = $repository->findBySearch($searchData);

            return $this->render('pages/group/index.html.twig', [
                'form' => $form,
                'groups' => $dataFound
            ]);
        }

        $groups = $paginator->paginate(
            $repository->findAllOrderByCreatedAt(),
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('pages/group/index.html.twig', [
            'form' => $form->createView(),
            'groups' => $groups,
        ]);
    }

    #[Route('/group/new', name: 'group.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager, ContactRepository $repository) : Response{

        $group = new Group();
        $form = $this->createForm(GroupType::class, $group, [
            'action_label' => 'Ajouter le groupe'
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $group = $form->getData();
            $memberIds = $form->get('members')->getData();

            if (count($memberIds) === 0) {
                $this->addFlash('error', 'Vous devez ajouter au moins un membre pour créer un groupe.');
                return $this->redirectToRoute('group.new');
            }

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

        $form = $this->createForm(GroupType::class, $group, [
            'action_label' => 'Modifier le groupe'
        ]);

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

    #[Route('/group/{groupId}/remove-member/{memberId}', name: 'group.remove.member', methods: ['GET'])]
    public function removeMemberFromGroup($groupId, $memberId, EntityManagerInterface $entityManager) : Response
    {
        $group = $entityManager->getRepository(Group::class)->find($groupId);
        $member = $entityManager->getRepository(Contact::class)->find($memberId);

        if (!$group || !$member) {
            throw $this->createNotFoundException('Le groupe ou le membre n\'a pas été trouvé.');
        }

        $group->removeMember($member);

        // Vérification si le groupe est vide
        if (count($group->getMembers()) === 0) {
            $entityManager->remove($group);
        } else {
            $entityManager->persist($group);
        }

        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le membre a bien été supprimé du groupe !'
        );

        return $this->redirectToRoute('group.index');
    }
}
