<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Model\Data;

use Chiaki\Page\Api\Data\ChiakiPageInterface;

class ChiakiPage extends \Magento\Framework\Api\AbstractExtensibleObject implements ChiakiPageInterface
{

    /**
     * Get chiakipage_id
     * @return string|null
     */
    public function getChiakipageId()
    {
        return $this->_get(self::CHIAKIPAGE_ID);
    }

    /**
     * Set chiakipage_id
     * @param string $chiakipageId
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setChiakipageId($chiakipageId)
    {
        return $this->setData(self::CHIAKIPAGE_ID, $chiakipageId);
    }

    /**
     * Get type
     * @return string|null
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * Set type
     * @param string $type
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setType($type)
    {
        return $this->setData(self::TYPE, $type);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Chiaki\Page\Api\Data\ChiakiPageExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Chiaki\Page\Api\Data\ChiakiPageExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Chiaki\Page\Api\Data\ChiakiPageExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get config_data
     * @return string|null
     */
    public function getConfigData()
    {
        return $this->_get(self::CONFIG_DATA);
    }

    /**
     * Set config_data
     * @param string $configData
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setConfigData($configData)
    {
        return $this->setData(self::CONFIG_DATA, $configData);
    }

    /**
     * Get entity_type
     * @return string|null
     */
    public function getEntityType()
    {
        return $this->_get(self::ENTITY_TYPE);
    }

    /**
     * Set entity_type
     * @param string $entityType
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
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
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
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
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setUserId($userId)
    {
        return $this->setData(self::USER_ID, $userId);
    }

    /**
     * Get url
     * @return string|null
     */
    public function getUrl()
    {
        return $this->_get(self::URL);
    }

    /**
     * Set url
     * @param string $url
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Get addition_data
     * @return string|null
     */
    public function getAdditionData()
    {
        return $this->_get(self::ADDITION_DATA);
    }

    /**
     * Set addition_data
     * @param string $additionData
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     */
    public function setAdditionData($additionData)
    {
        return $this->setData(self::ADDITION_DATA, $additionData);
    }
}

