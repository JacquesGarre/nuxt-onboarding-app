<?php

namespace App\Controller;

use App\Entity\PartnerPack;
use App\Form\PartnerPackType;
use App\Repository\PartnerPackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/partnerpacks')]
class PartnerPackController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/', name: 'app_partner_pack_index', methods: ['GET'])]
    public function index(PartnerPackRepository $partnerPackRepository): Response
    {
        return $this->render('admin/partner_pack/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'partner_packs' => $partnerPackRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_partner_pack_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartnerPackRepository $partnerPackRepository): Response
    {
        $partnerPack = new PartnerPack();
        $form = $this->createForm(PartnerPackType::class, $partnerPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerPackRepository->save($partnerPack, true);

            return $this->redirectToRoute('app_partner_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/partner_pack/new.html.twig', [
            'partner_pack' => $partnerPack,
            'form' => $form,
            'currentUser' => $this->security->getUser(),
        ]);
    }

    #[Route('/{id}', name: 'app_partner_pack_show', methods: ['GET'])]
    public function show(PartnerPack $partnerPack): Response
    {
        return $this->render('admin/partner_pack/show.html.twig', [
            'partner_pack' => $partnerPack,
            'currentUser' => $this->security->getUser(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_partner_pack_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PartnerPack $partnerPack, PartnerPackRepository $partnerPackRepository): Response
    {
        $form = $this->createForm(PartnerPackType::class, $partnerPack);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerPackRepository->save($partnerPack, true);

            return $this->redirectToRoute('app_partner_pack_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/partner_pack/edit.html.twig', [
            'partner_pack' => $partnerPack,
            'form' => $form,
            'currentUser' => $this->security->getUser(),
        ]);
    }

    #[Route('/{id}', name: 'app_partner_pack_delete', methods: ['POST'])]
    public function delete(Request $request, PartnerPack $partnerPack, PartnerPackRepository $partnerPackRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partnerPack->getId(), $request->request->get('_token'))) {
            $partnerPackRepository->remove($partnerPack, true);
        }

        return $this->redirectToRoute('app_partner_pack_index', [], Response::HTTP_SEE_OTHER);
    }
}
