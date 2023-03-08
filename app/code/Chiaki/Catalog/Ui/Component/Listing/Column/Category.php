<?php

namespace Chiaki\Catalog\Ui\Component\Listing\Column;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Category extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var ProductFactory
     */
    protected $productFactory;

    /**
     * @var CategoryFactory
     */
    protected $categoryFactory;

    /**
     * Category constructor.
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param ProductFactory     $productFactory
     * @param CategoryFactory    $categoryFactory
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        ProductFactory $productFactory,
        CategoryFactory $categoryFactory,
        array $components = [],
        array $data = []
    ) {
        $this->productFactory  = $productFactory;
        $this->categoryFactory = $categoryFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $fieldName = $this->getData('name');
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $productId  = $item['entity_id'];
                $product    = $this->productFactory->create()->load($productId);
                $cats       = $product->getCategoryIds();
                $categories = array();
                if (count($cats)) {
                    foreach ($cats as $cat) {
                        $category     = $this->categoryFactory->create()->load($cat);
                        $categories[] = $category->getName();
                    }
                }
                $item[$fieldName] = implode(',', $categories);
            }
        }
        return $dataSource;
    }
}
