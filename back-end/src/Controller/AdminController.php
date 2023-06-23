<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\UserRepository;

class AdminController extends AbstractController
{   
    private $security;
    private $userRepository;

    public function __construct(
        Security $security, 
        UserRepository $userRepository
    )
    {
       $this->security = $security;
       $this->userRepository = $userRepository;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard/dashboard.html.twig', [
            'currentUser' => $this->security->getUser(),
            'totalUsers' => count($this->userRepository->findAll()),
            'totalPublishedArticles' => 0,
            'totalPendingArticles' => 0,
            'totalRanks' => 0,
            'totalActiveAchievements' => 0,
            'totalPendingAchievements' => 0
        ]);
    }

}
