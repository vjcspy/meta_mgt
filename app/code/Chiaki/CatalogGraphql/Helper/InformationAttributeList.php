<?php


namespace Chiaki\CatalogGraphql\Helper;


use Magento\Framework\Exception\NoSuchEntityException;

class InformationAttributeList
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     * @throws NoSuchEntityException
     */
    public function getList(): \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
    {
        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection */
        $collection = $this->collectionFactory->create();
        $collection->setItemObjectClass(\Magento\Catalog\Model\ResourceModel\Eav\Attribute::class)
            ->addStoreLabel($this->storeManager->getStore()->getId())
            ->setOrder('position', 'ASC');

        $collection = $this->_prepareAttributeCollection($collection);
        $collection->load();

        return $collection;

    }

    /**
     * Add filters to attribute collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    protected function _prepareAttributeCollection(\Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection $collection): \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
    {
        $collection->addFieldToFilter('additional_table.is_visible_on_front', ['gt' => 0]);
        return $collection;
    }
}
