<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Config\Api\Data;

interface ChiakiConfigSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ChiakiConfig list.
     * @return \Chiaki\Config\Api\Data\ChiakiConfigInterface[]
     */
    public function getItems();

    /**
     * Set key list.
     * @param \Chiaki\Config\Api\Data\ChiakiConfigInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

