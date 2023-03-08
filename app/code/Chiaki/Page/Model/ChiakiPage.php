<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Model;

use Chiaki\Page\Api\Data\ChiakiPageInterface;
use Chiaki\Page\Api\Data\ChiakiPageInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class ChiakiPage extends \Magento\Framework\Model\AbstractModel
{

    protected $chiakipageDataFactory;

    protected $_eventPrefix = 'chiaki_page_chiakipage';
    protected $dataObjectHelper;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ChiakiPageInterfaceFactory $chiakipageDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Chiaki\Page\Model\ResourceModel\ChiakiPage $resource
     * @param \Chiaki\Page\Model\ResourceModel\ChiakiPage\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ChiakiPageInterfaceFactory $chiakipageDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Chiaki\Page\Model\ResourceModel\ChiakiPage $resource,
        \Chiaki\Page\Model\ResourceModel\ChiakiPage\Collection $resourceCollection,
        array $data = []
    ) {
        $this->chiakipageDataFactory = $chiakipageDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve chiakipage model with chiakipage data
     * @return ChiakiPageInterface
     */
    public function getDataModel()
    {
        $chiakipageData = $this->getData();
        
        $chiakipageDataObject = $this->chiakipageDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $chiakipageDataObject,
            $chiakipageData,
            ChiakiPageInterface::class
        );
        
        return $chiakipageDataObject;
    }
}

