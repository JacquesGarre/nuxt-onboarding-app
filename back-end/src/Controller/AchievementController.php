<?php

namespace App\Controller;

use App\Entity\Achievement;
use App\Form\AchievementType;
use App\Repository\AchievementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/achievements')]
class AchievementController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    
    
    #[Route('/', name: 'app_achievement_index', methods: ['GET'])]
    public function index(AchievementRepository $achievementRepository): Response
    {
        return $this->render('admin/achievement/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'achievements' => $achievementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_achievement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AchievementRepository $achievementRepository): Response
    {
        $achievement = new Achievement();
        $form = $this->createForm(AchievementType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achievementRepository->save($achievement, true);

            return $this->redirectToRoute('app_achievement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/achievement/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'achievement' => $achievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achievement_show', methods: ['GET'])]
    public function show(Achievement $achievement): Response
    {
        return $this->render('admin/achievement/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'achievement' => $achievement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_achievement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Achievement $achievement, AchievementRepository $achievementRepository): Response
    {
        $form = $this->createForm(AchievementType::class, $achievement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $achievementRepository->save($achievement, true);

            return $this->redirectToRoute('app_achievement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/achievement/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'achievement' => $achievement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_achievement_delete', methods: ['POST'])]
    public function delete(Request $request, Achievement $achievement, AchievementRepository $achievementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$achievement->getId(), $request->request->get('_token'))) {
            $achievementRepository->remove($achievement, true);
        }

        return $this->redirectToRoute('app_achievement_index', [], Response::HTTP_SEE_OTHER);
    }
}
