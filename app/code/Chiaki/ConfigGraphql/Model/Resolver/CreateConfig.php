<?php


namespace Chiaki\ConfigGraphql\Model\Resolver;


use Chiaki\Config\Model\ChiakiConfigRepository;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CreateConfig implements ResolverInterface
{

    /**
     * @var ChiakiConfigRepository
     */
    private $chiakiConfigRepository;
    /**
     * @var \Chiaki\Config\Model\Data\ChiakiConfig
     */
    private $chiakiConfig;
    /**
     * @var \Chiaki\Config\Model\ResourceModel\ChiakiConfig\Collection
     */
    private $collection;

    public function __construct(
        ChiakiConfigRepository $chiakiConfigRepository,
        \Chiaki\Config\Model\Data\ChiakiConfig $chiakiConfig,
        \Chiaki\Config\Model\ResourceModel\ChiakiConfig\Collection $collection
    )
    {
        $this->chiakiConfigRepository = $chiakiConfigRepository;
        $this->chiakiConfig = $chiakiConfig;
        $this->collection = $collection;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }

        $this->chiakiConfig->setData('key', $args['input']['key']);
        $this->chiakiConfig->setData('value', $args['input']['value']);
        $this->chiakiConfig->setData('user_id', $args['input']['user_id']);
        $this->chiakiConfig->setData('store_id', $args['input']['store_id']);

        $this->chiakiConfigRepository->save($this->chiakiConfig);

        $this->collection->addFieldToFilter('key', $args['input']['key']);
        $this->collection->addFieldToFilter('user_id', $args['input']['user_id']);
        $this->collection->addFieldToFilter('store_id', $args['input']['store_id']);

        $item = $this->collection->getFirstItem();
        if($item->getData('id')){
            return $item->getData();
        }return null;
    }
}
