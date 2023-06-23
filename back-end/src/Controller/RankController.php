<?php

namespace App\Controller;

use App\Entity\Rank;
use App\Form\RankType;
use App\Repository\RankRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/ranks')]
class RankController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'app_rank_index', methods: ['GET'])]
    public function index(RankRepository $rankRepository): Response
    {
        return $this->render('admin/rank/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'ranks' => $rankRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rank_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RankRepository $rankRepository): Response
    {
        $rank = new Rank();
        $form = $this->createForm(RankType::class, $rank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rankRepository->save($rank, true);

            return $this->redirectToRoute('app_rank_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/rank/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'rank' => $rank,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rank_show', methods: ['GET'])]
    public function show(Rank $rank): Response
    {
        return $this->render('admin/rank/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'rank' => $rank,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rank_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rank $rank, RankRepository $rankRepository): Response
    {
        $form = $this->createForm(RankType::class, $rank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rankRepository->save($rank, true);

            return $this->redirectToRoute('app_rank_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/rank/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'rank' => $rank,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_rank_delete', methods: ['POST'])]
    public function delete(Request $request, Rank $rank, RankRepository $rankRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rank->getId(), $request->request->get('_token'))) {
            $rankRepository->remove($rank, true);
        }

        return $this->redirectToRoute('app_rank_index', [], Response::HTTP_SEE_OTHER);
    }
}
