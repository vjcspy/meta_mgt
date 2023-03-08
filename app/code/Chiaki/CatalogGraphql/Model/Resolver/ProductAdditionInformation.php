<?php


namespace Chiaki\CatalogGraphql\Model\Resolver;


use Chiaki\CatalogGraphql\Helper\InformationAttributeList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class ProductAdditionInformation implements ResolverInterface
{


    /**
     * @var \Magento\Catalog\Model\ProductRepository
     */
    private $productRepository;
    /**
     * @var InformationAttributeList
     */
    private $informationAttributeList;

    public function __construct(
        \Magento\Catalog\Model\ProductRepository $productRepository,
        InformationAttributeList $informationAttributeList
    )
    {
        $this->productRepository = $productRepository;
        $this->informationAttributeList = $informationAttributeList;
    }

    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['sku']) || empty($args['sku'])) {
            throw new LocalizedException(__('"Sku type should be specified'));
        }

        $data = [];
        $sku = $args['sku'];

        $product = $this->productRepository->get($sku);
        $attributes = $this->informationAttributeList->getList();
        foreach ($attributes as $attribute) {
            $attributeCode = $attribute->getAttributeCode();
            $attrVal = $product->getData($attributeCode);
            if (!empty($attrVal)) {
                $data[$attributeCode] = $product->getAttributeText($attributeCode);
            }
        }

        return [
            'id' => $product->getId(),
            'data' => json_encode($data)
        ];
    }
}
