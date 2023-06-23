<?php

namespace App\Controller;

use App\Entity\Quest;
use App\Form\QuestType;
use App\Repository\QuestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/quests')]
class QuestController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'app_quest_index', methods: ['GET'])]
    public function index(QuestRepository $questRepository): Response
    {
        return $this->render('admin/quest/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'quests' => $questRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, QuestRepository $questRepository): Response
    {
        $quest = new Quest();
        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questRepository->save($quest, true);

            return $this->redirectToRoute('app_quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/quest/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'quest' => $quest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quest_show', methods: ['GET'])]
    public function show(Quest $quest): Response
    {
        return $this->render('admin/quest/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'quest' => $quest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quest_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quest $quest, QuestRepository $questRepository): Response
    {
        $form = $this->createForm(QuestType::class, $quest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questRepository->save($quest, true);

            return $this->redirectToRoute('app_quest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/quest/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'quest' => $quest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_quest_delete', methods: ['POST'])]
    public function delete(Request $request, Quest $quest, QuestRepository $questRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quest->getId(), $request->request->get('_token'))) {
            $questRepository->remove($quest, true);
        }

        return $this->redirectToRoute('app_quest_index', [], Response::HTTP_SEE_OTHER);
    }
}
