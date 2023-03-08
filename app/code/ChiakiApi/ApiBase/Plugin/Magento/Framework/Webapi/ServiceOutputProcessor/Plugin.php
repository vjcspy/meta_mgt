<?php


namespace ChiakiApi\ApiBase\Plugin\Magento\Framework\Webapi\ServiceOutputProcessor;


use ChiakiApi\ApiBase\Model\Data\IzRetailResponse;

class Plugin
{
    public function aroundConvertValue($subject, $process, $data, $type)
    {
        if ($data instanceof IzRetailResponse) {
            return $data->__toArray();
        }

        return $process($data, $type);
    }
}
