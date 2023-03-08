<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Config\Model;

use Chiaki\Config\Api\ChiakiConfigRepositoryInterface;
use Chiaki\Config\Api\Data\ChiakiConfigInterfaceFactory;
use Chiaki\Config\Api\Data\ChiakiConfigSearchResultsInterfaceFactory;
use Chiaki\Config\Model\ResourceModel\ChiakiConfig as ResourceChiakiConfig;
use Chiaki\Config\Model\ResourceModel\ChiakiConfig\CollectionFactory as ChiakiConfigCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class ChiakiConfigRepository implements ChiakiConfigRepositoryInterface
{

    private $collectionProcessor;

    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $chiakiConfigCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    private $storeManager;

    protected $chiakiConfigFactory;

    protected $dataChiakiConfigFactory;

    protected $extensionAttributesJoinProcessor;

    protected $dataObjectHelper;


    /**
     * @param ResourceChiakiConfig $resource
     * @param ChiakiConfigFactory $chiakiConfigFactory
     * @param ChiakiConfigInterfaceFactory $dataChiakiConfigFactory
     * @param ChiakiConfigCollectionFactory $chiakiConfigCollectionFactory
     * @param ChiakiConfigSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceChiakiConfig $resource,
        ChiakiConfigFactory $chiakiConfigFactory,
        ChiakiConfigInterfaceFactory $dataChiakiConfigFactory,
        ChiakiConfigCollectionFactory $chiakiConfigCollectionFactory,
        ChiakiConfigSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->chiakiConfigFactory = $chiakiConfigFactory;
        $this->chiakiConfigCollectionFactory = $chiakiConfigCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataChiakiConfigFactory = $dataChiakiConfigFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Chiaki\Config\Api\Data\ChiakiConfigInterface $chiakiConfig
    ) {
        /* if (empty($chiakiConfig->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $chiakiConfig->setStoreId($storeId);
        } */
        
        $chiakiConfigData = $this->extensibleDataObjectConverter->toNestedArray(
            $chiakiConfig,
            [],
            \Chiaki\Config\Api\Data\ChiakiConfigInterface::class
        );
        
        $chiakiConfigModel = $this->chiakiConfigFactory->create()->setData($chiakiConfigData);
        
        try {
            $this->resource->save($chiakiConfigModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the chiakiConfig: %1',
                $exception->getMessage()
            ));
        }
        return $chiakiConfigModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($chiakiConfigId)
    {
        $chiakiConfig = $this->chiakiConfigFactory->create();
        $this->resource->load($chiakiConfig, $chiakiConfigId);
        if (!$chiakiConfig->getId()) {
            throw new NoSuchEntityException(__('ChiakiConfig with id "%1" does not exist.', $chiakiConfigId));
        }
        return $chiakiConfig->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->chiakiConfigCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Chiaki\Config\Api\Data\ChiakiConfigInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Chiaki\Config\Api\Data\ChiakiConfigInterface $chiakiConfig
    ) {
        try {
            $chiakiConfigModel = $this->chiakiConfigFactory->create();
            $this->resource->load($chiakiConfigModel, $chiakiConfig->getChiakiconfigId());
            $this->resource->delete($chiakiConfigModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ChiakiConfig: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($chiakiConfigId)
    {
        return $this->delete($this->get($chiakiConfigId));
    }
}

