<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordEncoderSubscriber implements EventSubscriberInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public static function getSubscribedEvents(){
        return [
            KernelEvents::VIEW => ['encodeUserPassword' , EventPriorities::PRE_WRITE]
        ];
    }

    public function encodeUserPassword(GetResponseForControllerResultEvent $event){
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if (!$user instanceof User || !in_array($method, [Request::METHOD_POST])) {
            return;
        }
        
        $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));
    }
}
