<?php

namespace Alcalyn\SerializerDoctrineProxies;

use Doctrine\ORM\PersistentCollection;
use Doctrine\ODM\MongoDB\PersistentCollection as MongoDBPersistentCollection;
use Doctrine\ODM\PHPCR\PersistentCollection as PHPCRPersistentCollection;
use Doctrine\Common\Persistence\Proxy;
use Doctrine\ORM\Proxy\Proxy as ORMProxy;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\Subscriber\DoctrineProxySubscriber as BaseDoctrineProxySubscriber;

class DoctrineProxySubscriber extends BaseDoctrineProxySubscriber
{
    /**
     * Whether lazy load entity relations or not.
     *
     * @var bool
     */
    private $enableLazyLoading;

    /**
     * @param bool $enableLazyLoading
     */
    public function __construct($enableLazyLoading = true)
    {
        $this->enableLazyLoading = $enableLazyLoading;
    }

    /**
     * {@InheritDoc}
     */
    public function onPreSerialize(PreSerializeEvent $event)
    {
        $object = $event->getObject();
        $type = $event->getType();

        // If the set type name is not an actual class, but a faked type for which a custom handler exists, we do not
        // modify it with this subscriber.
        // Also, we forgo autoloading here as an instance of this type is already created,
        // so it must be loaded if its a real class.
        $virtualType = ! class_exists($type['name'], false);

        if ($object instanceof PersistentCollection
            || $object instanceof MongoDBPersistentCollection
            || $object instanceof PHPCRPersistentCollection
        ) {
            if (!$virtualType) {
                $event->setType('ArrayCollection');
            }

            return;
        }

        if (!$object instanceof Proxy && !$object instanceof ORMProxy) {
            return;
        }

        if ($this->enableLazyLoading) {
            $object->__load();
        }

        if (!$virtualType) {
            if ($this->enableLazyLoading || $object->__isInitialized()) {
                $event->setType(get_parent_class($object));
            } else {
                $event->setType(SerializerProxyType::class, array(
                    'id' => $object->getId(),
                ));
            }
        }
    }
}
