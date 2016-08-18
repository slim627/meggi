<?php
namespace Meggi\IndexBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\EventSubscriber;

class ContainerListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postLoad',
        );
    }

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof ContainerAwareInterface){
            $entity->setContainer($this->container);
        }

    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        if($entity instanceof ContainerAwareInterface){
            $entity->setContainer($this->container);
        }
    }
}
