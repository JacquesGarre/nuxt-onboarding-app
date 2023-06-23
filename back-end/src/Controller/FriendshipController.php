<?php

namespace App\Controller;

use App\Entity\Friendship;
use App\Form\FriendshipType;
use App\Repository\FriendshipRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/users/{userID}/friendship')]
class FriendshipController extends AbstractController
{
    private $security;
    private $userRepository;


    public function __construct(Security $security, UserRepository $userRepository)
    {
       $this->security = $security;
       $this->userRepository = $userRepository;
    }
    

    #[Route('/', name: 'app_friendship_index', methods: ['GET'])]
    public function index(FriendshipRepository $friendshipRepository): Response
    {
        return $this->render('admin/friendship/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'friendships' => $friendshipRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_friendship_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FriendshipRepository $friendshipRepository, int $userID): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $userID]);

        $friendship = new Friendship();
        $friendship->setUser2($user);
        $form = $this->createForm(FriendshipType::class, $friendship, ['from_user' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $friendshipRepository->save($friendship, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/friendship/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'friendship' => $friendship,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_friendship_show', methods: ['GET'])]
    public function show(Friendship $friendship): Response
    {
        return $this->render('admin/friendship/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'friendship' => $friendship,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_friendship_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Friendship $friendship, FriendshipRepository $friendshipRepository): Response
    {
        $form = $this->createForm(FriendshipType::class, $friendship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $friendshipRepository->save($friendship, true);

            return $this->redirectToRoute('app_friendship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/friendship/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'friendship' => $friendship,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_friendship_delete', methods: ['POST'])]
    public function delete(Request $request, Friendship $friendship, FriendshipRepository $friendshipRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$friendship->getId(), $request->request->get('_token'))) {
            $friendshipRepository->remove($friendship, true);
        }

        return $this->redirectToRoute('app_friendship_index', [], Response::HTTP_SEE_OTHER);
    }
}
