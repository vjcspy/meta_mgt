<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Config\Model;

use Chiaki\Config\Api\Data\ChiakiConfigInterface;
use Chiaki\Config\Api\Data\ChiakiConfigInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class ChiakiConfig extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $chiakiconfigDataFactory;

    protected $_eventPrefix = 'chiaki_config_chiakiconfig';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ChiakiConfigInterfaceFactory $chiakiconfigDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Chiaki\Config\Model\ResourceModel\ChiakiConfig $resource
     * @param \Chiaki\Config\Model\ResourceModel\ChiakiConfig\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        ChiakiConfigInterfaceFactory $chiakiconfigDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Chiaki\Config\Model\ResourceModel\ChiakiConfig $resource,
        \Chiaki\Config\Model\ResourceModel\ChiakiConfig\Collection $resourceCollection,
        array $data = []
    ) {
        $this->chiakiconfigDataFactory = $chiakiconfigDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve chiakiconfig model with chiakiconfig data
     * @return ChiakiConfigInterface
     */
    public function getDataModel()
    {
        $chiakiconfigData = $this->getData();
        
        $chiakiconfigDataObject = $this->chiakiconfigDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $chiakiconfigDataObject,
            $chiakiconfigData,
            ChiakiConfigInterface::class
        );
        
        return $chiakiconfigDataObject;
    }
}

