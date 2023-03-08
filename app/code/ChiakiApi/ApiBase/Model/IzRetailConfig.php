<?php


namespace ChiakiApi\ApiBase\Model;


class IzRetailConfig
{
    private $configData;

    public function __construct(\Magento\Framework\Config\Data $configData)
    {
        $this->configData = $configData;
    }

    public function get()
    {
        return $this->configData->get();
    }
}
