<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserNodeRepository;
use App\Repository\FriendshipRepository;

#[Route('/admin/users')]
class UserController extends AbstractController
{
    private $security;
    private $userNodeRepository;
    private $friendshipRepository;

    public function __construct(Security $security, UserNodeRepository $userNodeRepository, FriendshipRepository $friendshipRepository)
    {
       $this->security = $security;
       $this->userNodeRepository = $userNodeRepository;
       $this->friendshipRepository = $friendshipRepository;
    }

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/users/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/users/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user_nodes' => $user->getUserNodes(),
            'user_epics' => $user->getUserEpics(),
            'user_stories' => $user->getUserStories(),
            'user_quests' => $user->getUserQuests(),
            'user_continents' => $user->getUserContinents(),
            'user_countries' => $user->getUserCountries(),
            'user_regions' => $user->getUserRegions(),
            'user_achievements' => $user->getUserAchievements(),
            'user_friends' => $this->friendshipRepository->getAllFriendshipOfUser($user),
            'user_invitations' => $user->getInvitations(),
            'user_subscriptions' => $user->getSubscriptions(),
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
