<?php

namespace Chiaki\ConfigGraphql\Model\Resolver;

use Chiaki\Config\Model\ResourceModel\ChiakiConfig\CollectionFactory;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Config implements ResolverInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    private $collection;

    /**
     * Config constructor.
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['user_id'])) {
            throw new GraphQlInputException(__("Please define user_id"));
        }
        if (!$this->collection) {
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('user_id', $args['user_id']);

            if (isset($args['key'])) {
                $collection->addFieldToFilter('key', $args['key']);
            }

            if (isset($args['store_id'])) {
                $collection->addFieldToFilter('store_id', $args['store_id']);
            }

            if ($collection->getSize() > 0) {
                $this->collection = $collection;
            } else {
                $collectionDefault = $this->collectionFactory->create();
                $collectionDefault->addFieldToFilter('user_id', 'default');
                if (isset($args['key'])) {
                    $collectionDefault->addFieldToFilter('key', $args['key']);
                }
                $collectionDefault->addFieldToFilter('store_id', 'default');
                if ($collectionDefault->getSize() > 0) {
                    $this->collection = $collectionDefault;
                } else {
                    $collection0 = $this->collectionFactory->create();
                    $collection0->addFieldToFilter('user_id', 0);
                    if (isset($args['key'])) {
                        $collection0->addFieldToFilter('key', $args['key']);
                    }
                    $collection0->addFieldToFilter('store_id', 0);
                    $this->collection = $collection0;
                }
            }
        }

        $data = [];

        foreach ($this->collection as $item) {
            $data[] = $item->getData();
        }

        return $data;
    }
}
