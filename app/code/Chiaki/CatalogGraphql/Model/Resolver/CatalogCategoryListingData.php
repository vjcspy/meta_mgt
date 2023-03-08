<?php


namespace Chiaki\CatalogGraphql\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 *
 * Tam thoi se reuse lai default. Do la su dung elasticsearch de lay ra items sau do join voi mgt table de lay full item data
 * Elastic search tra ve 2 thong tin do la: items(item_id) va aggregations
 *
 * Sau nay co the  can customize lai data, luc do can phai hieu co che index elatic search. Lam sao de day duoc cac
 * aggregation(bucket) vao elastic search
 *
 * Class CatalogCategoryListingData
 * @package Chiaki\CatalogGraphql\Model\Resolver
 */
class CatalogCategoryListingData extends \Magento\CatalogGraphQl\Model\Resolver\Products implements ResolverInterface
{

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (isset($args['filters'])) {
            $filter = [];
            foreach ($args['filters'] as $filterData) {
                if ($filterData['code'] === 'price') {
                    if(isset($filterData['data']['eq'])){
                        $prices = explode("_", $filterData['data']['eq']);
                    }
                    if(isset($prices) && is_array($prices) && count($prices) == 2){
                        $filter[$filterData['code']] = [
                            'from' => $prices[0],
                            'to' => $prices[1]
                        ];
                    }
                } else {
                    $filter[$filterData['code']] = $filterData['data'];
                }

            }
            $args['filter'] = $filter;
        }
        return parent::resolve($field, $context, $info, $value, $args);
    }
}
