<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Config\Model\Data;

use Chiaki\Config\Api\Data\ChiakiConfigInterface;

class ChiakiConfig extends \Magento\Framework\Api\AbstractExtensibleObject implements ChiakiConfigInterface
{

    /**
     * Get chiakiconfig_id
     * @return string|null
     */
    public function getChiakiconfigId()
    {
        return $this->_get(self::CHIAKICONFIG_ID);
    }

    /**
     * Set chiakiconfig_id
     * @param string $chiakiconfigId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setChiakiconfigId($chiakiconfigId)
    {
        return $this->setData(self::CHIAKICONFIG_ID, $chiakiconfigId);
    }

    /**
     * Get key
     * @return string|null
     */
    public function getKey()
    {
        return $this->_get(self::KEY);
    }

    /**
     * Set key
     * @param string $key
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setKey($key)
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Chiaki\Config\Api\Data\ChiakiConfigExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Chiaki\Config\Api\Data\ChiakiConfigExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Chiaki\Config\Api\Data\ChiakiConfigExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get value
     * @return string|null
     */
    public function getValue()
    {
        return $this->_get(self::VALUE);
    }

    /**
     * Set value
     * @param string $value
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }

    /**
     * Get user_id
     * @return string|null
     */
    public function getUserId()
    {
        return $this->_get(self::USER_ID);
    }

    /**
     * Set user_id
     * @param string $userId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * Set store_id
     * @param string $storeId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }
}

