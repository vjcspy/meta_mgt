<?php


namespace ChiakiApi\ApiBase\Api\Data;


interface IzRetailResponseInterface
{
    const DATA = "data";
    const ERROR = "error";

    /**
     * Action Type
     *
     * @return \ChiakiApi\ApiBase\Model\Data\IzRetailData
     */
    public function getData();

    /**
     * Set Action type
     *
     * @param array $data
     * @return $this
     */
    public function setData($data);

    /**
     * Action Payload
     *
     * @return \ChiakiApi\ApiBase\Model\Data\IzRetailData
     */
    public function getError();

    /**
     * Set Action Payload
     *
     * @param array $error
     * @return $this
     */
    public function setError($error);
}
