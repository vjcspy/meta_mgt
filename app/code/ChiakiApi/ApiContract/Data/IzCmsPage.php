<?php


namespace ChiakiApi\ApiContract\Data;


class IzCmsPage extends AbstractApiDataObject
{
    public function getPageId()
    {
        return $this->getData('page_id');
    }

    public function getTitle()
    {
        return $this->getData('title');
    }

    public function getContentHeading()
    {
        return $this->getData('content_heading');
    }

    public function getContent()
    {
        return $this->getData('content');
    }

    public function getDisplayOn()
    {
        return $this->getData('type');
    }

    public function getImageHeader()
    {
        return $this->getData('image_header');
    }

    public function getUpdatedTime()
    {
        return $this->getData('update_time');
    }

    public function getIsClmVoucher()
    {
        return $this->getData('is_clm_voucher');
    }

    public function getIsClmProduct()
    {
        return $this->getData('is_clm_product');
    }

    public function getClmVoucherData(){
        return $this->getData('clm_voucher_data');
    }

    public function getIsActive(){
        return $this->getData('is_active');
    }

    public function getCityCode(){
        return $this->getData('apply_city_code');
    }

    public function getIsBanner(){
        return $this->getData('is_banner');
    }
}
