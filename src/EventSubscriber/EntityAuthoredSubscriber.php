<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\AuthoredEntityInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class EntityAuthoredSubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setEntityAuthor', EventPriorities::PRE_WRITE]
        ];
    }

    public function setEntityAuthor(GetResponseForControllerResultEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        $token = $this->token->getToken();

        if (null === $token) {
            return;
        }

        /** @var UserInterface $author */
        $author = $token->getUser();

        if (!$entity instanceof AuthoredEntityInterface || Request::METHOD_POST != $method) {
            return;
        }

        $entity->setAuthor($author);
    }
}