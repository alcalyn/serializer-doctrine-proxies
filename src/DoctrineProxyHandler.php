<?php

namespace Alcalyn\SerializerDoctrineProxies;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use JMS\Serializer\XmlSerializationVisitor;

class DoctrineProxyHandler implements SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => SerializerProxyType::class,
                'format' => 'json',
                'method' => 'serializeToJsonOrYml',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => SerializerProxyType::class,
                'format' => 'yml',
                'method' => 'serializeToJsonOrYml',
            ),
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'type' => SerializerProxyType::class,
                'format' => 'xml',
                'method' => 'serializeToXml',
            ),
        );
    }

    /**
     * @param VisitorInterface $visitor
     * @param type $entity
     * @param array $type
     * @param Context $context
     *
     * @return \stdClass
     */
    public function serializeToJsonOrYml(VisitorInterface $visitor, $entity, array $type, Context $context)
    {
        $object = new \stdClass();
        $object->id = $type['params']['id'];

        return $object;
    }

    /**
     * @param XmlSerializationVisitor $visitor
     * @param type $entity
     * @param array $type
     * @param Context $context
     *
     * @return \DOMElement
     */
    public function serializeToXml(XmlSerializationVisitor $visitor, $entity, array $type, Context $context)
    {
        $visitor->getCurrentNode()->appendChild(
            $formNode = $visitor->getDocument()->createElement('id', $type['params']['id'])
        );

        return $formNode;
    }
}
