<?php

namespace App\EventListener;

use App\Entity\Friendship;
use App\Entity\User;
use App\Repository\InvitationRepository;
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
        InvitationRepository $invitationRepository,
        VerifyEmailHelperInterface $verifyEmailHelper,
        MailerInterface $mailer
    )
    {
        $this->hasher = $hasher;
        $this->invitationRepository = $invitationRepository;
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

        // Set experience to 0
        $entity->setExperience(0);

        // Set verified to false
        $entity->setIsVerified(false);

        // Init all credits to 0
        $initialCredit = in_array('ROLE_ADMIN', $entity->getRoles()) ? 100000 : 0;
        $entity->setNbPrivateArticlesLeft($initialCredit);
        $entity->setNbPrivateAchievementsLeft($initialCredit);
        $entity->setNbPrivateQuestsLeft($initialCredit);
        $entity->setNbPrivateStoriesLeft($initialCredit);
        $entity->setNbPrivateEpicsLeft($initialCredit);
        $entity->setNbPrivatePlacesLeft($initialCredit);
        $entity->setNbSponsoredArticlesLeft($initialCredit);
        $entity->setNbSponsoredAchievementsLeft($initialCredit);
        $entity->setNbSponsoredQuestsLeft($initialCredit);
        $entity->setNbSponsoredStoriesLeft($initialCredit);
        $entity->setNbSponsoredEpicsLeft($initialCredit);
        $entity->setNbSponsoredPlacesLeft($initialCredit);

    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
    
        if (!$entity instanceof User) {
            return;
        }

        $entityManager = $args->getObjectManager();

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
               


        // If new user was invited
        // Get all related invitations, get user from them, and add new user as a friend to those invitations
        // Then update invitation to accepted
        $invitations = $this->invitationRepository->findBy([
            'email' => $entity->getEmail(),
            'status' => 'pending'
        ]);

        foreach($invitations as $invitation){

            // Add friendship
            $user1 = $invitation->getUser();
            $user2 = $entity;

            $friendship = new Friendship();
            $friendship->setUser1($user1);
            $friendship->setUser2($user2);

            $entityManager->persist($friendship);
            $entityManager->flush();

            // Update invitation to accepted
            $invitation->setStatus('accepted');
            $invitation->setAcceptedAt(new DateTimeImmutable());
            $entityManager->persist($invitation);
            $entityManager->flush();

        }

    }

}