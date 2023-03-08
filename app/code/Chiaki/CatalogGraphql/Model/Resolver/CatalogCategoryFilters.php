<?php
/**
 *
 * @author Khoi Le - mr.vjcspy@gmail.com
 * @time 9/5/20 8:50 PM
 *
 */

namespace Chiaki\CatalogGraphql\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CatalogCategoryFilters implements ResolverInterface
{
    /**
     * @var \Magento\Catalog\Model\Layer\FilterList
     */
    private $filterList;
    /**
     * @var \Magento\Catalog\Model\Layer\Category
     */
    private $categoryLayer;
    /**
     * @var \Magento\Swatches\Helper\Data
     */
    private $swatchHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\Category $categoryLayer,
        \Magento\Swatches\Helper\Data $swatchHelper
    )
    {
        $this->filterList = $filterList;
        $this->categoryLayer = $categoryLayer;
        $this->swatchHelper = $swatchHelper;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        return array_map(function ($filter) {
            $attrModel = $filter->getAttributeModel();
            $options = [];

            /** @var \Magento\Catalog\Model\Layer\Filter\Item $item */
            foreach ($filter->getItems() as $item) {
                $options[] = [
                    "label" => $item->getLabel(),
                    "count" => $item->getData('count'),
                    "value" => $item->getValueString()
                ];
            }
            $swatches = [];
            if ($this->swatchHelper->isSwatchAttribute($attrModel)) {
                $optionIds = [];
                foreach ($attrModel->getFrontend()->getSelectOptions() as $selectOption) {
                    if ($selectOption['value']) {
                        $optionIds[] = $selectOption['value'];
                    }
                }

                $swatches = array_values($this->swatchHelper->getSwatchesByOptionsId($optionIds));
            }


            return [
                "name" => $filter->getName(),
                "type" => $attrModel->getFrontendInput(),
                "fe_model" => $attrModel->getData('frontend_model'),
                "item_count" => $filter->getItemsCount(),
                "options" => $options,
                "swatches" => $swatches
            ];
        }, $this->getFilters($args['category_id']));
    }

    /**
     * @param $categoryId
     * @return array|\Magento\Catalog\Model\Layer\Filter\AbstractFilter[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFilters($categoryId)
    {
        $this->categoryLayer->setCurrentCategory($categoryId);

        $filters = $this->filterList->getFilters($this->categoryLayer);

        return array_filter($filters, static function ($abstractFilter) {
            return (bool)$abstractFilter->getData('attribute_model') && $abstractFilter->getItemsCount() > 0;
        });
    }
}
