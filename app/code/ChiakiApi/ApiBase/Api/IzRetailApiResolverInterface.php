<?php


namespace ChiakiApi\ApiBase\Api;


interface IzRetailApiResolverInterface
{
    /**
     * POST for ChiakiApi Management api
     * @param \ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface $action
     * @return \ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function dispatch(\ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface $action);
}
