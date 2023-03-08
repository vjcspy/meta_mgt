<?php
/**
 * Created by mr.vjcspy@gmail.com - khoild@smartosc.com.
 * Date: 27/11/2016
 * Time: 19:07
 */

namespace ChiakiApi\ApiContract\Data;

use ChiakiApi\ApiContract\Data\AbstractApiDataObject;

class IzCustomerGroup extends AbstractApiDataObject
{

    public function getId()
    {
        return $this->getData('customer_group_id');
    }

    public function getCode()
    {
        return $this->getData('customer_group_code');
    }

    public function getTaxClassId()
    {
        return $this->getData('tax_class_id');
    }

    public function getTaxClassName()
    {
        return $this->getData('tax_class_name');
    }

}
