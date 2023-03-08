<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Api\Data;

interface ChiakiPageInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const STORE_ID = 'store_id';
    const CONFIG_DATA = 'config_data';
    const CHIAKIPAGE_ID = 'chiakipage_id';
    const ADDITION_DATA = 'addition_data';
    const USER_ID = 'user_id';
    const TYPE = 'type';
    const ENTITY_TYPE = 'entity_type';
    const URL = 'url';

    /**
     * Get chiakipage_id
     * @return string|null
     */
    public function getChiakipageId();

    /**
     * Set chiakipage_id
     * @param string $chiakipageId
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setChiakipageId($chiakipageId);

    /**
     * Get type
     * @return string|null
     */
    public function getType();

    /**
     * Set type
     * @param string $type
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setType($type);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Chiaki\Page\Api\Data\ChiakiPageExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Chiaki\Page\Api\Data\ChiakiPageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Chiaki\Page\Api\Data\ChiakiPageExtensionInterface $extensionAttributes
    );

    /**
     * Get config_data
     * @return string|null
     */
    public function getConfigData();

    /**
     * Set config_data
     * @param string $configData
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setConfigData($configData);

    /**
     * Get entity_type
     * @return string|null
     */
    public function getEntityType();

    /**
     * Set entity_type
     * @param string $entityType
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setEntityType($entityType);

    /**
     * Get store_id
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     * @param string $storeId
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setStoreId($storeId);

    /**
     * Get user_id
     * @return string|null
     */
    public function getUserId();

    /**
     * Set user_id
     * @param string $userId
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setUserId($userId);

    /**
     * Get url
     * @return string|null
     */
    public function getUrl();

    /**
     * Set url
     * @param string $url
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setUrl($url);

    /**
     * Get addition_data
     * @return string|null
     */
    public function getAdditionData();

    /**
     * Set addition_data
     * @param string $additionData
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setAdditionData($additionData);
}

