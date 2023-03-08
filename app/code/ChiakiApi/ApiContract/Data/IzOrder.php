<?php
/**
 * Created by mr.vjcspy@gmail.com - khoild@smartosc.com.
 * Date: 07/01/2017
 * Time: 16:16
 */


namespace ChiakiApi\ApiContract\Data;


use ChiakiApi\ApiContract\Data\AbstractApiDataObject;

class IzOrder extends AbstractApiDataObject
{

    public function getOrderId()
    {
        return $this->getData('entity_id');
    }

    public function getIncrementId()
    {
        preg_match('/(.*)(-.*-)(.*)/m', $this->getData('increment_id'), $re);
        if (count($re) === 4) {
            return $re[1] . $re[3];
        }

        return $this->getData('increment_id');
    }

    public function getStatus()
    {
        return $this->getData('status');
    }

    public function getState()
    {
        return $this->getData('state');
    }

    public function getCustomer()
    {
        return $this->getData('customer');
    }

    public function getItems()
    {
        return $this->getData('items');
    }

    public function getCanCreditmemo()
    {
        return $this->getData('can_creditmemo');
    }

    public function getCanShip()
    {
        return $this->getData('can_ship');
    }

    public function getCanInvoice()
    {
        return $this->getData('can_invoice');
    }

    public function getBillingAddress()
    {
        if ($billingAdd = $this->getData('billing_address'))
            return $billingAdd;
        else
            return [];
    }

    public function getShippingAddress()
    {
        if ($shippingAdd = $this->getData('shipping_address'))
            return $shippingAdd;
        else
            return [];
    }

    public function getPayment()
    {
        return $this->getData('payment');
    }

    public function getTotals()
    {
        return $this->getData('totals');
    }

    public function getCreatedAt()
    {
        return $this->getData('created_at');
    }

    public function getShippingMethod()
    {
        return $this->getData('shipping_method');
    }

    public function getDeliveryTime()
    {
        return $this->getData('delivery_time');
    }

    public function getIntegrationData()
    {
        try{
            return json_decode($this->getData('integration_data'));
        }catch (\Exception $e){
            return [];
        }
    }

    public function getRetailAdditionalData()
    {
        return $this->getData('retail_additional_data');
    }

    public function getThirdPartyData()
    {
        return $this->getData('third_party_data');
    }

    public function getStoreId()
    {
        return $this->getData('store_id');
    }
}
