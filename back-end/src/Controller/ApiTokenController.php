<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Form\ApiTokenType;
use App\Repository\ApiTokenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/tokens')]
class ApiTokenController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'app_api_token_index', methods: ['GET'])]
    public function index(ApiTokenRepository $apiTokenRepository): Response
    {
        return $this->render('admin/api_token/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'api_tokens' => $apiTokenRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_api_token_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ApiTokenRepository $apiTokenRepository): Response
    {
        $apiToken = new ApiToken();
        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiTokenRepository->save($apiToken, true);

            return $this->redirectToRoute('app_api_token_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/api_token/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'api_token' => $apiToken,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_api_token_show', methods: ['GET'])]
    public function show(ApiToken $apiToken): Response
    {
        return $this->render('admin/api_token/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'api_token' => $apiToken,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_api_token_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApiToken $apiToken, ApiTokenRepository $apiTokenRepository): Response
    {
        $form = $this->createForm(ApiTokenType::class, $apiToken);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiTokenRepository->save($apiToken, true);

            return $this->redirectToRoute('app_api_token_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/api_token/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'api_token' => $apiToken,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_api_token_delete', methods: ['POST'])]
    public function delete(Request $request, ApiToken $apiToken, ApiTokenRepository $apiTokenRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$apiToken->getId(), $request->request->get('_token'))) {
            $apiTokenRepository->remove($apiToken, true);
        }

        return $this->redirectToRoute('app_api_token_index', [], Response::HTTP_SEE_OTHER);
    }
}
