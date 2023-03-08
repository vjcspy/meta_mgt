<?php
/**
 * Created by mr.vjcspy@gmail.com - khoild@smartosc.com.
 * Date: 28/10/2016
 * Time: 10:33
 */

namespace ChiakiApi\ApiContract\Data;

class IzStore extends AbstractApiDataObject
{

    public function getId()
    {
        return $this->getData('store_id');
    }

    public function getCode()
    {
        return $this->getData('code');
    }

    public function getWebsiteId()
    {
        return $this->getData('website_id');
    }

    public function getGroupId()
    {
        return $this->getData('group_id');
    }

    public function getName()
    {
        return $this->getData('name');
    }

    public function getSortOrder()
    {
        return $this->getData('sort_order');
    }

    public function getIsActive()
    {
        return $this->getData('is_active');
    }

    public function getBaseCurrency()
    {
        return $this->getData('base_currency');
    }

    public function getCurrentCurrency()
    {
        return $this->getData('current_currency');
    }

    public function getRate()
    {
        return $this->getData('rate');
    }

    public function getPriceFormat()
    {
        return $this->getData('price_format');
    }

    public function getLogoImage()
    {
        return $this->getData('logo');
    }

    public function getBrandName()
    {
        return $this->getData('brand_name');
    }

    public function getStoreLocatorData()
    {
        return $this->getData('store_locator_data');
    }
}
