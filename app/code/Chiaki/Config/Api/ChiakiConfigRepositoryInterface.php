<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Config\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ChiakiConfigRepositoryInterface
{

    /**
     * Save ChiakiConfig
     * @param \Chiaki\Config\Api\Data\ChiakiConfigInterface $chiakiConfig
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Chiaki\Config\Api\Data\ChiakiConfigInterface $chiakiConfig
    );

    /**
     * Retrieve ChiakiConfig
     * @param string $chiakiconfigId
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($chiakiconfigId);

    /**
     * Retrieve ChiakiConfig matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Chiaki\Config\Api\Data\ChiakiConfigSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ChiakiConfig
     * @param \Chiaki\Config\Api\Data\ChiakiConfigInterface $chiakiConfig
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Chiaki\Config\Api\Data\ChiakiConfigInterface $chiakiConfig
    );

    /**
     * Delete ChiakiConfig by ID
     * @param string $chiakiconfigId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($chiakiconfigId);
}

