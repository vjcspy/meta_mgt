<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ChiakiPageRepositoryInterface
{

    /**
     * Save ChiakiPage
     * @param \Chiaki\Page\Api\Data\ChiakiPageInterface $chiakiPage
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Chiaki\Page\Api\Data\ChiakiPageInterface $chiakiPage
    );

    /**
     * Retrieve ChiakiPage
     * @param string $chiakipageId
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($chiakipageId);

    /**
     * Retrieve ChiakiPage matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Chiaki\Page\Api\Data\ChiakiPageSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ChiakiPage
     * @param \Chiaki\Page\Api\Data\ChiakiPageInterface $chiakiPage
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Chiaki\Page\Api\Data\ChiakiPageInterface $chiakiPage
    );

    /**
     * Delete ChiakiPage by ID
     * @param string $chiakipageId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($chiakipageId);
}

