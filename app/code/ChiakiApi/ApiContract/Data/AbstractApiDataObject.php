<?php

namespace ChiakiApi\ApiContract\Data;

use ChiakiApi\ApiContract\Model\DataObject;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use ReflectionException;

abstract class AbstractApiDataObject extends DataObject
{

    /**
     * get Method
     */
    public const GET_METHOD = 'get';
    /**
     * @var []
     */
    protected $_dataOutput;

    /**
     * @var []
     */
    protected $_allGetApiMethod;

    protected $_serializer;

    /**
     * Data as array
     *
     * @return array
     * @throws ReflectionException
     */
    public function getOutput(): ?array
    {
        if ($this->_dataOutput === null) {
            $methods = $this->getAllGetApiMethod();
            foreach ($methods as $method) {
                if (strpos($method, self::GET_METHOD) === 0) {
                    $key = $this->_underscore(substr($method, 3));
                    $this->_dataOutput[$key] = call_user_func_array([$this, $method], []);
                    if ($this->_dataOutput[$key] instanceof self) {
                        $this->_dataOutput[$key] = $this->_dataOutput[$key]->getOutput();
                    }
                }
            }
        }

        return $this->_dataOutput;
    }

    /**
     * @return array get method
     * @throws ReflectionException
     */
    public function getAllGetApiMethod(): ?array
    {
        if ($this->_allGetApiMethod === null) {
            $class = new \ReflectionClass(get_class($this));
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                if ($method->getDeclaringClass()->getName() === get_class($this)) {
                    $this->_allGetApiMethod[] = $method->getName();
                }

            }
        }

        return $this->_allGetApiMethod;
    }

    /**
     * @param $value
     * @return string|int|float|bool|array|null
     */
    protected function unserialize($value)
    {
        if (class_exists(Json::class)) {
            return $this->getSerialize()->unserialize($value);
        }

        throw new \RuntimeException('can not unserialize');
    }

    /**
     * @return Json
     */
    protected function getSerialize(): Json
    {
        if ($this->_serializer === null) {
            $this->_serializer = ObjectManager::getInstance()->create(Json::class);
        }

        return $this->_serializer;
    }
}
