<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Api\Data;

interface ChiakiPageSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get ChiakiPage list.
     * @return \Chiaki\Page\Api\Data\ChiakiPageInterface[]
     */
    public function getItems();

    /**
     * Set type list.
     * @param \Chiaki\Page\Api\Data\ChiakiPageInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

