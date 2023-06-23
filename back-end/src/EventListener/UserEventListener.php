<?php

namespace App\EventListener;

use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail as Email;

class UserEventListener
{
    private $hasher;
    private $invitationRepository;
    private $verifyEmailHelper;
    private $mailer;

    public function __construct(
        UserPasswordHasherInterface $hasher, 
        VerifyEmailHelperInterface $verifyEmailHelper,
        MailerInterface $mailer
    )
    {
        $this->hasher = $hasher;
        $this->verifyEmailHelper = $verifyEmailHelper;
        $this->mailer = $mailer;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if (!$entity instanceof User) {
            return;
        }

        // hash password before saving
        $hashedPassword = $this->hasher->hashPassword(
            $entity,
            $entity->getPassword()
        );
        $entity->setPassword($hashedPassword);

        // add role_user by default
        if(!in_array('ROLE_USER', $entity->getRoles())){
            $roles = $entity->getRoles();
            $roles[] = 'ROLE_USER';
            $entity->setRoles($roles);
        }

        // Set verified to false
        $entity->setIsVerified(false);
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
    
        if (!$entity instanceof User) {
            return;
        }

        // Send verification email
        if($entity->isVerified() === false){
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                'app_verify_email',
                $entity->getId(),
                $entity->getEmail(),
                ['id' => $entity->getId()]
            );
            $email = (new Email())
            ->from('fogo.app.contact@gmail.com')
            ->to($entity->getEmail())
            ->subject('FOGO Verification: Please confirm your email')
            ->htmlTemplate('emails/verification.html.twig')
            ->context([
                'user' => $entity,
                'url' => $signatureComponents->getSignedUrl()
            ]);
            $this->mailer->send($email);
        }
               
    }

}