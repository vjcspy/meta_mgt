<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Model\ResourceModel\ChiakiPage;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'chiakipage_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            \Chiaki\Page\Model\ChiakiPage::class,
            \Chiaki\Page\Model\ResourceModel\ChiakiPage::class
        );
    }
}

