<?php


namespace Chiaki\CatalogGraphql\Model\Resolver;


use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\Resolver\ValueFactory;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class SwatchAttribute implements ResolverInterface
{

    /**
     * @var ValueFactory
     */
    private $valueFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute
     */
    private $attribute;
    /**
     * @var \Magento\Swatches\Helper\Data
     */
    private $swatchHelper;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute,
        \Magento\Swatches\Helper\Data $swatchHelper,
        ValueFactory $valueFactory
    )
    {
        $this->valueFactory = $valueFactory;
        $this->attribute = $attribute;
        $this->swatchHelper = $swatchHelper;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        return $this->valueFactory->create(
            function () use ($value) {
                $entityType = $this->getEntityType($value);
                $attributeCode = $this->getAttributeCode($value);
                $attribute = $this->attribute->loadByCode($entityType, $attributeCode);
                if ($this->swatchHelper->isSwatchAttribute($attribute)) {
                    $optionIds = [];
                    foreach ($attribute->getFrontend()->getSelectOptions() as $selectOption) {
                        if ($selectOption['value']) {
                            $optionIds[] = $selectOption['value'];
                        }
                    }

                    return array_values($this->swatchHelper->getSwatchesByOptionsId($optionIds));
                }
                return [];
            }
        );
    }

    /**
     * Get entity type
     *
     * @param array $value
     * @return string
     * @throws LocalizedException
     */
    private function getEntityType(array $value): string
    {
        if (!isset($value['entity_type'])) {
            throw new LocalizedException(__('"Entity type should be specified'));
        }

        return $value['entity_type'];
    }

    /**
     * Get attribute code
     *
     * @param array $value
     * @return string
     * @throws LocalizedException
     */
    private function getAttributeCode(array $value): string
    {
        if (!isset($value['attribute_code'])) {
            throw new LocalizedException(__('"Attribute code should be specified'));
        }

        return $value['attribute_code'];
    }
}
