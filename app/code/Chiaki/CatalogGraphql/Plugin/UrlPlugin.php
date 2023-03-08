<?php

namespace Chiaki\CatalogGraphql\Plugin;

use Chiaki\Catalog\Helper\Data;
use Exception;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\ImageFactory;
use Magento\CatalogGraphQl\Model\Resolver\Product\MediaGallery\Url;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class UrlPlugin
{
    /**
     * @var ImageFactory
     */
    private $productImageFactory;

    /**
     * @var PlaceholderProvider
     */
    private $placeholderProvider;

    /**
     * @var Data
     */
    private $helper;

    /**
     * @var string[]
     */
    private $placeholderCache = [];

    /**
     * UrlPlugin constructor.
     *
     * @param ImageFactory $productImageFactory
     * @param PlaceholderProvider $placeholderProvider
     * @param Data $helper
     */
    public function __construct(
        ImageFactory        $productImageFactory,
        PlaceholderProvider $placeholderProvider,
        Data                $helper
    )
    {
        $this->productImageFactory = $productImageFactory;
        $this->placeholderProvider = $placeholderProvider;
        $this->helper = $helper;
    }

    /**
     * @param Url $subject
     * @param callable $proceed
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     */
    public function aroundResolve(
        Url $subject, callable $proceed, Field $field,
            $context, ResolveInfo $info, array $value = null, array $args = null
    )
    {
        if ($this->helper->isEnableResize() && isset($value['image_type']) && $value['image_type'] = 'small_image') {
            if (!isset($value['image_type']) && !isset($value['file'])) {
                throw new LocalizedException(__('"image_type" value should be specified'));
            }

            if (!isset($value['model'])) {
                throw new LocalizedException(__('"model" value should be specified'));
            }

            /** @var Product $product */
            $product = $value['model'];
            if (isset($value['image_type'])) {
                $imagePath = $product->getData($value['image_type']);
                return $this->getImageUrl($value['image_type'], $imagePath);
            }

            if (isset($value['file'])) {
                return $this->getImageUrl('image', $value['file']);
            }
            return [];
        }

        return $proceed($field, $context, $info, $value, $args);
    }

    /**
     * Get image URL
     *
     * @param string $imageType
     * @param string|null $imagePath
     *
     * @return string
     * @throws Exception
     */
    private function getImageUrl(string $imageType, ?string $imagePath): string
    {
        if (empty($imagePath) && !empty($this->placeholderCache[$imageType])) {
            return $this->placeholderCache[$imageType];
        }
        $newWidth = floatval($this->helper->getWidth());
        $newHeight = floatval($this->helper->getHeight());
        $resizeImage = $this->helper->getResizeImage($imagePath, $newWidth, $newHeight);
        if ($resizeImage) {
            $this->placeholderCache[$imageType] = $resizeImage;
            return $resizeImage;
        }
        $this->placeholderCache[$imageType] = $this->placeholderProvider->getPlaceholder($imageType);
        return $this->placeholderCache[$imageType];
    }
}
