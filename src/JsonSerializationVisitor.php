<?php

namespace Alcalyn\SerializerDoctrineProxies;

use JMS\Serializer\JsonSerializationVisitor as BaseJsonSerializationVisitor;

class JsonSerializationVisitor extends BaseJsonSerializationVisitor
{
    /**
     * {@InheritDoc}
     *
     * And convert ArrayObjects to php regular array.
     *
     * Patch this issue: https://github.com/schmittjoh/JMSSerializerBundle/issues/373
     */
    public function getResult()
    {
        return parent::getResult();
        if ($this->getRoot() instanceof \ArrayObject) {
            $this->setRoot((array) $this->getRoot());
        }

    }
}
