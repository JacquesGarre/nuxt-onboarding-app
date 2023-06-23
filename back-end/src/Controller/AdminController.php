<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\RankRepository;
use App\Repository\AchievementRepository;

class AdminController extends AbstractController
{   
    private $security;
    private $userRepository;
    private $articleRepository;
    private $rankRepository;
    private $achievementRepository;

    public function __construct(
        Security $security, 
        UserRepository $userRepository, 
        ArticleRepository $articleRepository,
        RankRepository $rankRepository,
        AchievementRepository $achievementRepository
    )
    {
       $this->security = $security;
       $this->userRepository = $userRepository;
       $this->articleRepository = $articleRepository;
       $this->rankRepository = $rankRepository;
       $this->achievementRepository = $achievementRepository;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/dashboard.html.twig', [
            'currentUser' => $this->security->getUser(),
            'totalUsers' => count($this->userRepository->findAll()),
            'totalPublishedArticles' => count($this->articleRepository->findBy(['status' => 'published'])),
            'totalPendingArticles' => count($this->articleRepository->findBy(['status' => 'draft'])),
            'totalRanks' => count($this->rankRepository->findAll()),
            'totalActiveAchievements' => count($this->achievementRepository->findBy(['status' => 'active'])),
            'totalPendingAchievements' => count($this->achievementRepository->findBy(['status' => 'draft']))
        ]);
    }

}
