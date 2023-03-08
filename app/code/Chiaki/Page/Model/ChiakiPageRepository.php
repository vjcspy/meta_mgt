<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Page\Model;

use Chiaki\Page\Api\ChiakiPageRepositoryInterface;
use Chiaki\Page\Api\Data\ChiakiPageInterfaceFactory;
use Chiaki\Page\Api\Data\ChiakiPageSearchResultsInterfaceFactory;
use Chiaki\Page\Model\ResourceModel\ChiakiPage as ResourceChiakiPage;
use Chiaki\Page\Model\ResourceModel\ChiakiPage\CollectionFactory as ChiakiPageCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;

class ChiakiPageRepository implements ChiakiPageRepositoryInterface
{

    private $storeManager;

    protected $dataChiakiPageFactory;

    protected $dataObjectProcessor;

    protected $chiakiPageFactory;

    protected $chiakiPageCollectionFactory;

    private $collectionProcessor;

    protected $extensibleDataObjectConverter;
    protected $searchResultsFactory;

    protected $extensionAttributesJoinProcessor;

    protected $resource;

    protected $dataObjectHelper;


    /**
     * @param ResourceChiakiPage $resource
     * @param ChiakiPageFactory $chiakiPageFactory
     * @param ChiakiPageInterfaceFactory $dataChiakiPageFactory
     * @param ChiakiPageCollectionFactory $chiakiPageCollectionFactory
     * @param ChiakiPageSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceChiakiPage $resource,
        ChiakiPageFactory $chiakiPageFactory,
        ChiakiPageInterfaceFactory $dataChiakiPageFactory,
        ChiakiPageCollectionFactory $chiakiPageCollectionFactory,
        ChiakiPageSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->chiakiPageFactory = $chiakiPageFactory;
        $this->chiakiPageCollectionFactory = $chiakiPageCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataChiakiPageFactory = $dataChiakiPageFactory;
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
        \Chiaki\Page\Api\Data\ChiakiPageInterface $chiakiPage
    ) {
        /* if (empty($chiakiPage->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $chiakiPage->setStoreId($storeId);
        } */
        
        $chiakiPageData = $this->extensibleDataObjectConverter->toNestedArray(
            $chiakiPage,
            [],
            \Chiaki\Page\Api\Data\ChiakiPageInterface::class
        );
        
        $chiakiPageModel = $this->chiakiPageFactory->create()->setData($chiakiPageData);
        
        try {
            $this->resource->save($chiakiPageModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the chiakiPage: %1',
                $exception->getMessage()
            ));
        }
        return $chiakiPageModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($chiakiPageId)
    {
        $chiakiPage = $this->chiakiPageFactory->create();
        $this->resource->load($chiakiPage, $chiakiPageId);
        if (!$chiakiPage->getId()) {
            throw new NoSuchEntityException(__('ChiakiPage with id "%1" does not exist.', $chiakiPageId));
        }
        return $chiakiPage->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->chiakiPageCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Chiaki\Page\Api\Data\ChiakiPageInterface::class
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
        \Chiaki\Page\Api\Data\ChiakiPageInterface $chiakiPage
    ) {
        try {
            $chiakiPageModel = $this->chiakiPageFactory->create();
            $this->resource->load($chiakiPageModel, $chiakiPage->getChiakipageId());
            $this->resource->delete($chiakiPageModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ChiakiPage: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($chiakiPageId)
    {
        return $this->delete($this->get($chiakiPageId));
    }
}

