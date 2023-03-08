<?php

namespace Chiaki\Catalog\Model\Category;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class CategoryList implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * CategoryList constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->_categoryCollectionFactory = $collectionFactory;
    }

    public function toOptionArray($addEmpty = true)
    {

        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $options = [];
        if ($addEmpty) {
            $options[] = ['label' => __('-- Please Select a Category --'), 'value' => ''];
        }
        foreach ($collection as $category) {
            $options[] = ['label' => $category->getName(), 'value' => $category->getId()];
        }
        return $options;
    }
}
