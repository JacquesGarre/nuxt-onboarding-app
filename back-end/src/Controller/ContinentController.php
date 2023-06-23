<?php

namespace App\Controller;

use App\Entity\Continent;
use App\Form\ContinentType;
use App\Repository\ContinentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('/admin/continents')]
class ContinentController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    #[Route('/', name: 'app_continent_index', methods: ['GET'])]
    public function index(ContinentRepository $continentRepository): Response
    {
        return $this->render('admin/continent/index.html.twig', [
            'continents' => $continentRepository->findAll(),
            'currentUser' => $this->security->getUser(),
        ]);
    }

    #[Route('/new', name: 'app_continent_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContinentRepository $continentRepository): Response
    {
        $continent = new Continent();
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $continentRepository->save($continent, true);

            return $this->redirectToRoute('app_continent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/continent/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'continent' => $continent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_continent_show', methods: ['GET'])]
    public function show(Continent $continent): Response
    {
        return $this->render('admin/continent/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'continent' => $continent,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_continent_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Continent $continent, ContinentRepository $continentRepository): Response
    {
        $form = $this->createForm(ContinentType::class, $continent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $continentRepository->save($continent, true);

            return $this->redirectToRoute('app_continent_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/continent/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'continent' => $continent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_continent_delete', methods: ['POST'])]
    public function delete(Request $request, Continent $continent, ContinentRepository $continentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$continent->getId(), $request->request->get('_token'))) {
            $continentRepository->remove($continent, true);
        }

        return $this->redirectToRoute('app_continent_index', [], Response::HTTP_SEE_OTHER);
    }
}
