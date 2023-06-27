<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

class DuplicateEntryExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof \Doctrine\DBAL\Exception\UniqueConstraintViolationException) {
            $errorMessage = 'email: This email is already in use.';
            $response = new Response(
                json_encode(['detail' => $errorMessage]),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['Content-Type' => 'application/json']
            );
            $event->setResponse($response);
        }
    }
}