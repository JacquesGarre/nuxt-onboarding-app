<?php

namespace App\Controller;

use App\Entity\Epic;
use App\Form\EpicType;
use App\Repository\EpicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/epics')]
class EpicController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'app_epic_index', methods: ['GET'])]
    public function index(EpicRepository $epicRepository): Response
    {
        return $this->render('admin/epic/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'epics' => $epicRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_epic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EpicRepository $epicRepository): Response
    {
        $epic = new Epic();
        $form = $this->createForm(EpicType::class, $epic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $epicRepository->save($epic, true);

            return $this->redirectToRoute('app_epic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/epic/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'epic' => $epic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_epic_show', methods: ['GET'])]
    public function show(Epic $epic): Response
    {
        return $this->render('admin/epic/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'epic' => $epic,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_epic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Epic $epic, EpicRepository $epicRepository): Response
    {
        $form = $this->createForm(EpicType::class, $epic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $epicRepository->save($epic, true);

            return $this->redirectToRoute('app_epic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/epic/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'epic' => $epic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_epic_delete', methods: ['POST'])]
    public function delete(Request $request, Epic $epic, EpicRepository $epicRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$epic->getId(), $request->request->get('_token'))) {
            $epicRepository->remove($epic, true);
        }

        return $this->redirectToRoute('app_epic_index', [], Response::HTTP_SEE_OTHER);
    }
}
