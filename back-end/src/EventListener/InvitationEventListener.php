<?php

namespace App\EventListener;


use DateTimeImmutable;
use App\Entity\Invitation;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail as Email;
class InvitationEventListener
{
    private $security;
    private $mailer;


    public function __construct(Security $security, MailerInterface $mailer)
    {
       $this->security = $security;
       $this->mailer = $mailer;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Invitation) {
            return;
        }
        $entity->setCreatedAt(new DateTimeImmutable());
        $entity->setStatus('pending');
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof Invitation) {
            return;
        }
        $email = (new Email())
        ->from('fogo.app.contact@gmail.com')
        ->to($entity->getEmail())
        ->subject('FOGO App: You have been invited by '.$entity->getUser()->getFirstName().' '.$entity->getUser()->getLastName())
        ->htmlTemplate('emails/invitation.html.twig')
        ->context([
            'user' => $entity->getUser(),
            'url' => '#'
        ]);
        $this->mailer->send($email);
    }


}