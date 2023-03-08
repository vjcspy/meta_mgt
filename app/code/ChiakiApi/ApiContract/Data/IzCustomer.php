<?php
/**
 * Created by mr.vjcspy@gmail.com - khoild@smartosc.com.
 * Date: 24/10/2016
 * Time: 15:48
 */

namespace ChiakiApi\ApiContract\Data;

use ChiakiApi\ApiContract\Data\AbstractApiDataObject;

class IzCustomer extends AbstractApiDataObject
{

    public function getId()
    {
        return $this->getData('entity_id');
    }

    public function getCustomerGroupId()
    {
        return $this->getData('group_id');
    }

    public function getDefaultBilling()
    {
        return $this->getData('default_billing');
    }

    public function getDefaultShipping()
    {
        return $this->getData('default_shipping');
    }

    public function getEmail()
    {
        return $this->getData('email');
    }

    public function getFirstName()
    {
        return $this->getData('firstname');
    }

    public function getLastName()
    {
        return $this->getData('lastname');
    }

    public function getGender()
    {
        return $this->getData('gender');
    }

    public function getStoreId()
    {
        return $this->getData('store_id');
    }

    public function getWebsiteId()
    {
        return $this->getData('website_id');
    }

    public function getAddress()
    {
        return $this->getData('address');
    }

    public function getTelephone()
    {
        return $this->getData('telephone');
    }

}
