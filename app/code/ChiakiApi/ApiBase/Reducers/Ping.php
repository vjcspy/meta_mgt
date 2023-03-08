<?php


namespace ChiakiApi\ApiBase\Reducers;


use ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface;
use ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface;
use ChiakiApi\ApiBase\Api\IzRetailReducer;

class Ping implements IzRetailReducer
{

    public function reduce(IzRetailActionInterface $action, IzRetailResponseInterface $response)
    {
        $response->setData(['success' => 'true']);
    }
}
