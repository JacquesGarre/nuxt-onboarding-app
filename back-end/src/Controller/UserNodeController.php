<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserNode;
use App\Form\UserNodeType;
use App\Repository\UserNodeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('admin/users/{userID}/map')]
class UserNodeController extends AbstractController
{
    private $security;
    private $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
       $this->security = $security;
       $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'app_user_node_index', methods: ['GET'])]
    public function index(UserNodeRepository $userNodeRepository, int $userID): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $userID]);
        return $this->render('admin/user_node/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user_nodes' => $userNodeRepository->findAll(),
            'user' => $user
        ]);
    }

    #[Route('/new', name: 'app_user_node_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserNodeRepository $userNodeRepository, int $userID): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $userID]);

        $userNode = new UserNode();
        $userNode->setUser($user);
        $form = $this->createForm(UserNodeType::class, $userNode, ['from_user' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userNodeRepository->save($userNode, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_node/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user_node' => $userNode,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_user_node_show', methods: ['GET'])]
    public function show(UserNode $userNode, int $userID): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $userID]);
        return $this->render('admin/user_node/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user_node' => $userNode,
            'user' => $user
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_node_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserNode $userNode, UserNodeRepository $userNodeRepository, int $userID): Response
    {   
        $user = $this->userRepository->findOneBy(['id' => $userID]);
        $form = $this->createForm(UserNodeType::class, $userNode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userNodeRepository->save($userNode, true);

            return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user_node/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'user_node' => $userNode,
            'form' => $form,
            'user' => $user
        ]);
    }

    #[Route('/{id}', name: 'app_user_node_delete', methods: ['POST'])]
    public function delete(Request $request, UserNode $userNode, UserNodeRepository $userNodeRepository, int $userID): Response
    {   
        $user = $this->userRepository->findOneBy(['id' => $userID]);
        if ($this->isCsrfTokenValid('delete'.$userNode->getId(), $request->request->get('_token'))) {
            $userNodeRepository->remove($userNode, true);
        }

        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }
}
