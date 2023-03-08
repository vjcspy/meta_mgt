<?php


namespace ChiakiApi\ApiBase\Model\Data;


use ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class IzRetailAction extends AbstractSimpleObject implements IzRetailActionInterface
{

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @inheritDoc
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @inheritDoc
     */
    public function getPayload()
    {
        return $this->_get(self::PAYLOAD);
    }

    /**
     * @inheritDoc
     */
    public function setPayload($payload)
    {
        return $this->setData(self::PAYLOAD, $payload);
    }
}
