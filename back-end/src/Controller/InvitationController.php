<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Form\InvitationType;
use App\Repository\InvitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;

#[Route('/admin/users/{userID}/invitation')]
class InvitationController extends AbstractController
{
    private $security;
    private $userRepository;


    public function __construct(Security $security, UserRepository $userRepository)
    {
       $this->security = $security;
       $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'app_invitation_index', methods: ['GET'])]
    public function index(InvitationRepository $invitationRepository): Response
    {
        return $this->render('admin/invitation/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'invitations' => $invitationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_invitation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InvitationRepository $invitationRepository, int $userID): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $userID]);
        $invitation = new Invitation();
        $invitation->setUser($user);
        $form = $this->createForm(InvitationType::class, $invitation, ['from_user' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invitationRepository->save($invitation, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/invitation/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'invitation' => $invitation,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_invitation_show', methods: ['GET'])]
    public function show(Invitation $invitation): Response
    {
        return $this->render('admin/invitation/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'invitation' => $invitation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_invitation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invitation $invitation, InvitationRepository $invitationRepository): Response
    {
        $form = $this->createForm(InvitationType::class, $invitation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $invitationRepository->save($invitation, true);

            return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/invitation/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'invitation' => $invitation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_invitation_delete', methods: ['POST'])]
    public function delete(Request $request, Invitation $invitation, InvitationRepository $invitationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invitation->getId(), $request->request->get('_token'))) {
            $invitationRepository->remove($invitation, true);
        }

        return $this->redirectToRoute('app_invitation_index', [], Response::HTTP_SEE_OTHER);
    }
}
