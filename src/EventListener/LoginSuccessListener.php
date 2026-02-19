<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

class LoginSuccessListener
{
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData(); // contient le token
        $user = $event->getUser();

        if ($user instanceof UserInterface) {
            $data['user'] = [
                'id' => $user->getId(),
                'name' => $user->getUsername(),
                'email' => $user->getEmail(),
                'role' => $user->getRoles()[0] ?? 'client',
            ];
        }

        $event->setData($data);
    }
}
