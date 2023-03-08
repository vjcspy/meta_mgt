<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\CatalogGraphql\Model\Resolver\Product\MediaGallery;

use Chiaki\Catalog\Helper\Data;
use Magento\CatalogGraphQl\Model\Resolver\Products\DataProvider\Image\Placeholder as PlaceholderProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Returns media url
 */
class Url implements ResolverInterface
{

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
     * @param PlaceholderProvider $placeholderProvider
     * @param Data                $helper
     */
    public function __construct(
        PlaceholderProvider $placeholderProvider,
        Data $helper
    ) {
        $this->placeholderProvider = $placeholderProvider;
        $this->helper              = $helper;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        if (!isset($value['model'])) {
            throw new LocalizedException(__('"model" value should be specified'));
        }

        if (isset($value['file'])) {
            return $this->getImageUrl('image', $value['file']);
        }
        return [];
    }

    /**
     * Get image URL
     *
     * @param string      $imageType
     * @param string|null $imagePath
     *
     * @return string
     * @throws \Exception
     */
    private function getImageUrl(string $imageType, ?string $imagePath): string
    {
        if (empty($imagePath) && !empty($this->placeholderCache[$imageType])) {
            return $this->placeholderCache[$imageType];
        }
        $newWidth    = $this->helper->getWidth();
        $newHeight   = $this->helper->getHeight();
        $resizeImage = $this->helper->getResizeImage($imagePath, $newWidth, $newHeight);
        if ($resizeImage) {
            $this->placeholderCache[$imageType] = $resizeImage;
            return $resizeImage;
        }
        $this->placeholderCache[$imageType] = $this->placeholderProvider->getPlaceholder($imageType);
        return $this->placeholderCache[$imageType];
    }
}
