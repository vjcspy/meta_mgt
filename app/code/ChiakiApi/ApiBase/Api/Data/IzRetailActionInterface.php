<?php


namespace ChiakiApi\ApiBase\Api\Data;


use Magento\Framework\Api\ExtensibleDataInterface;

interface IzRetailActionInterface
{
    const TYPE = 'type';
    const PAYLOAD = 'payload';

    /**
     * Action Type
     *
     * @return string
     */
    public function getType();

    /**
     * Set Action type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Action Payload
     *
     * @return \ChiakiApi\ApiBase\Api\Data\IzRetailDataInterface
     */
    public function getPayload();

    /**
     * Set Action Payload
     *
     * @param array $payload
     * @return $this
     */
    public function setPayload($payload);
}
