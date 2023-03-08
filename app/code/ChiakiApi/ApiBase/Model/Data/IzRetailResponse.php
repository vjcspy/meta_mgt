<?php


namespace ChiakiApi\ApiBase\Model\Data;


use ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface;

class IzRetailResponse implements IzRetailResponseInterface
{
    /**
     * @var array
     */
    protected $_data;

    /**
     * Initialize internal storage
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->_data = $data;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->_get(self::DATA);
    }

    /**
     * @inheritDoc
     */
    public function setData($data)
    {
        $this->_data[self::DATA] = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getError()
    {
        return $this->_get(self::ERROR);
    }

    /**
     * @inheritDoc
     */
    public function setError($error)
    {
        $this->_data[self::ERROR] = $error;
        return $this;
    }

    /**
     * Retrieves a value from the data array if set, or null otherwise.
     *
     * @param string $key
     * @return mixed|null
     */
    protected function _get($key)
    {
        return $this->_data[$key] ?? null;
    }

    /**
     * Return Data Object data in array format.
     *
     * @return array
     */
    public function __toArray()
    {
        $data = $this->_data;
        $hasToArray = function ($model) {
            return is_object($model) && method_exists($model, '__toArray') && is_callable([$model, '__toArray']);
        };
        foreach ($data as $key => $value) {
            if ($hasToArray($value)) {
                $data[$key] = $value->__toArray();
            } elseif (is_array($value)) {
                foreach ($value as $nestedKey => $nestedValue) {
                    if ($hasToArray($nestedValue)) {
                        $value[$nestedKey] = $nestedValue->__toArray();
                    }
                }
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
