<?php

namespace App\Service;

use App\Entity\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Symfony\Component\HttpKernel\KernelInterface;

class MobileNotificationService {

    private $appKernel;
    private $factory;
    private $messaging;

    public function __construct(KernelInterface $appKernel)
    {
        $projectRoot = $appKernel->getProjectDir();
        $this->factory = (new Factory)->withServiceAccount($projectRoot.$_ENV['FIREBASE_CONFIG']);
        $this->messaging = $this->factory->createMessaging();
    }

    public function sendNotification(string $title, string $body, User $user)
    {
        $token = $user->getFcmToken();
        if(empty($token)){
            return;
        }
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification(Notification::create($title, $body));
        $this->messaging->send($message);
    }

}