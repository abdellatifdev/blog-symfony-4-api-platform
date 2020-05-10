<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class UserRegistrationSubscriber implements EventSubscriberInterface
{

    // private $jWTCreatedEvent;
    // public function __construct(JWTCreatedEvent $jWTCreatedEvent)
    // {
    //     $this->jWTCreatedEvent = $jWTCreatedEvent;
    // }

    // public static function getSubscribedEvents(){
    //     return [
    //         KernelEvents::VIEW => ['setJwt' , EventPriorities::POST_WRITE]
    //     ];
    // }

    // public function setJwt(GetResponseForControllerResultEvent $event,JWTCreatedEvent $jWTCreatedEvent)
    // {
    //     dd('tes');
    // }
}
