<?php
/**
 *
 * @author Khoi Le - mr.vjcspy@gmail.com
 * @time 6/18/20 2:06 PM
 *
 */

namespace ChiakiApi\ApiContract\Data;

class IzSetting extends AbstractApiDataObject
{

    public function getKey()
    {
        return $this->getData('key');
    }

    public function getValue()
    {
        return $this->getData('value');
    }

    public function getStoreId(){
        return $this->getData('store_id');
    }

}
