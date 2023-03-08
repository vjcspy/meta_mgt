<?php


namespace ChiakiApi\ApiBase\Api;


interface IzRetailReducer
{
    /**
     * POST for ChiakiApi Management api
     * @param \ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface $action
     * @param \ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface $response
     * @throws \Magento\Framework\Webapi\Exception
     */
    public function reduce(\ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface $action, \ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface $response);

}
