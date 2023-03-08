<?php


namespace ChiakiApi\ApiBase\Plugin\Magento\Framework\Webapi\ServiceInputProcessor;


use ChiakiApi\ApiBase\Model\Data\IzRetailData;

class Plugin
{
    public function aroundConvertValue($subject, $process, $data, $type)
    {
        if (strpos($type, 'IzRetailDataInterface') > 0) {
            return $data;
        }

        return $process($data, $type);
    }
}
