<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Config\Api\Data;

interface ChiakiConfigInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const KEY = 'key';
    const STORE_ID = 'store_id';
    const CHIAKICONFIG_ID = 'chiakiconfig_id';
    const USER_ID = 'user_id';
    const VALUE = 'value';

    /**
     * Get chiakiconfig_id
     * @return string|null
     */
    public function getChiakiconfigId();

    /**
     * Set chiakiconfig_id
     * @param string $chiakiconfigId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setChiakiconfigId($chiakiconfigId);

    /**
     * Get key
     * @return string|null
     */
    public function getKey();

    /**
     * Set key
     * @param string $key
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setKey($key);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Chiaki\Config\Api\Data\ChiakiConfigExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Chiaki\Config\Api\Data\ChiakiConfigExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Chiaki\Config\Api\Data\ChiakiConfigExtensionInterface $extensionAttributes
    );

    /**
     * Get value
     * @return string|null
     */
    public function getValue();

    /**
     * Set value
     * @param string $value
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setValue($value);

    /**
     * Get user_id
     * @return string|null
     */
    public function getUserId();

    /**
     * Set user_id
     * @param string $userId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setUserId($userId);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $storeId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     */
    public function setStoreId($storeId);
}

