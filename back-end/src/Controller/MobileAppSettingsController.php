<?php

namespace App\Controller;

use App\Entity\MobileAppSettings;
use App\Form\MobileAppSettingsType;
use App\Repository\MobileAppSettingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route('admin/settings')]
class MobileAppSettingsController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }


    #[Route('/', name: 'app_mobile_app_settings_index', methods: ['GET'])]
    public function index(MobileAppSettingsRepository $mobileAppSettingsRepository): Response
    {
        return $this->render('admin/mobile_app_settings/index.html.twig', [
            'currentUser' => $this->security->getUser(),
            'mobile_app_settings' => $mobileAppSettingsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mobile_app_settings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, MobileAppSettingsRepository $mobileAppSettingsRepository): Response
    {
        $mobileAppSetting = new MobileAppSettings();
        $form = $this->createForm(MobileAppSettingsType::class, $mobileAppSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mobileAppSettingsRepository->save($mobileAppSetting, true);

            return $this->redirectToRoute('app_mobile_app_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/mobile_app_settings/new.html.twig', [
            'currentUser' => $this->security->getUser(),
            'mobile_app_setting' => $mobileAppSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mobile_app_settings_show', methods: ['GET'])]
    public function show(MobileAppSettings $mobileAppSetting): Response
    {
        return $this->render('admin/mobile_app_settings/show.html.twig', [
            'currentUser' => $this->security->getUser(),
            'mobile_app_setting' => $mobileAppSetting,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mobile_app_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, MobileAppSettings $mobileAppSetting, MobileAppSettingsRepository $mobileAppSettingsRepository): Response
    {
        $form = $this->createForm(MobileAppSettingsType::class, $mobileAppSetting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mobileAppSettingsRepository->save($mobileAppSetting, true);

            return $this->redirectToRoute('app_mobile_app_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/mobile_app_settings/edit.html.twig', [
            'currentUser' => $this->security->getUser(),
            'mobile_app_setting' => $mobileAppSetting,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mobile_app_settings_delete', methods: ['POST'])]
    public function delete(Request $request, MobileAppSettings $mobileAppSetting, MobileAppSettingsRepository $mobileAppSettingsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mobileAppSetting->getId(), $request->request->get('_token'))) {
            $mobileAppSettingsRepository->remove($mobileAppSetting, true);
        }

        return $this->redirectToRoute('app_mobile_app_settings_index', [], Response::HTTP_SEE_OTHER);
    }
}
