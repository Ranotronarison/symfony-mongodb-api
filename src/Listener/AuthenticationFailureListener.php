<?php

namespace App\Listener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticationFailureListener
{
    public function onJWTNotFound(JWTNotFoundEvent $event)
    {
        $data = [
          'status'  => '403 Forbidden',
          'message' => 'AUTHENTICATION_FAILED',
        ];

        $response = new JsonResponse($data, 403);
        $event->setResponse($response);
    }
}
