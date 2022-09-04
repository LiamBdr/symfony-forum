<?php

namespace App\EventSubscriber;

use App\Model\TimestampedInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['setCreatedAt'],
            BeforeEntityUpdatedEvent::class => ['setUpdatedAt'],
        ];
    }

    public function setCreatedAt(BeforeEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof TimestampedInterface) {
            return;
        }

        $entity->setCreatedAt(new \DateTime());
        $entity->setUpdatedAt(new \DateTime());
    }

    public function setUpdatedAt(BeforeEntityUpdatedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if (!$entity instanceof TimestampedInterface) {
            return;
        }

        $entity->setUpdatedAt(new \DateTime());
    }
}