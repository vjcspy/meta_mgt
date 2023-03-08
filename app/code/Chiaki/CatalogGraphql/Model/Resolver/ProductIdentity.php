<?php


namespace Chiaki\CatalogGraphql\Model\Resolver;


use Magento\Framework\GraphQl\Query\Resolver\IdentityInterface;

class ProductIdentity implements IdentityInterface
{
    /** @var string */
    private $cacheTag = \Magento\Catalog\Model\Product::CACHE_TAG + '_a_d';

    /**
     * Get category ID from resolved data
     *
     * @param array $resolvedData
     * @return string[]
     */
    public function getIdentities(array $resolvedData): array
    {
        return empty($resolvedData['id']) ?
            [] : [$this->cacheTag, sprintf('%s_%s', $this->cacheTag, $resolvedData['id'])];
    }
}
