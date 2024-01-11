<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Group;
use App\Form\ContactType;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);

        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $searchData->page = $request->query->getInt('page', 1);
            $dataFound = $repository->findBySearch($searchData);

            return $this->render('pages/contact/index.html.twig', [
                'form' => $form,
                'contacts' => $dataFound
            ]);
        }

        $contacts = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/contact/index.html.twig', [
            'form' => $form->createView(),
            'contacts' => $contacts,
        ]);
    }

    #[Route('/new', name: 'contact.new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager) : Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact, [
            'action_label' => 'Ajouter le contact'
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();

//            $selectedGroupIds = $form->get('memberGroups')->getData();
//            foreach ($selectedGroupIds as $groupId) {
//
//                $group = $manager->getRepository(Group::class)->find($groupId);
//                if ($group) {
//                    $contact->addMemberGroup($group);
//                }
//            }

            $manager->persist($contact);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le contact a bien été ajouté !'
            );

            return $this->redirectToRoute('contact.index');
        }

        return $this->render('pages/contact/new.html.twig',[
            'form' => $form->createView()
            ]
        );
    }

    #[Route('/edit/{id}', name: 'contact.edit', methods: ['GET', 'POST'])]
    public function update(Contact $contact, Request $request, EntityManagerInterface $manager) : Response{

        $form = $this->createForm(ContactType::class, $contact, [
            'action_label' => 'Modifier le contact'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $contact = $form->getData();
            $postData = $request->request->all();
            $imageDeleted = isset($postData['contact']['imageDeleted']) && $postData['contact']['imageDeleted'] === '1';
            // Vérifie si on a supprimé l'image dans le formulaire
            if ($imageDeleted) {
                $contact->setImageName(null);
                $contact->setImageFile(null);
            }

            $manager->persist($contact);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le contact a bien été modifié !'
            );

            return $this->redirectToRoute('contact.index');
        }

        return $this->render('/pages/contact/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'contact.delete', methods: ['GET'])]
    public function delete(Contact $contact, EntityManagerInterface $manager) : Response{
        $manager->remove($contact);
        $manager->flush();

        $this->addFlash(
            'success',
            'Le contact a bien été modifié !'
        );

        return $this->redirectToRoute('contact.index');
    }
}
