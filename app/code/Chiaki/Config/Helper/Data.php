<?php


namespace Chiaki\Config\Helper;


class Data
{
    /**
     * @var \Chiaki\Config\Model\ResourceModel\ChiakiConfig\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(\Chiaki\Config\Model\ResourceModel\ChiakiConfig\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    public function getChiakiConfig($userId, $key, $storeId = 'default')
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('key', $key);
        $collection->addFieldToFilter('user_id', $userId);
        $collection->addFieldToFilter('store_id', $storeId);

        $item = $collection->getFirstItem();
        if ($item->getData('id')) {
            return $item->getData();
        }
        return null;
    }
}
