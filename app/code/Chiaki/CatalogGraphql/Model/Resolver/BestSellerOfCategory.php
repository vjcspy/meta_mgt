<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\CatalogGraphql\Model\Resolver;

use Magento\CatalogGraphQl\Model\Resolver\Products\Query\ProductQueryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Products field resolver, used for GraphQL request processing.
 */
class BestSellerOfCategory implements ResolverInterface
{

    /**
     * @var ProductQueryInterface
     */
    private $searchQuery;

    /**
     * @param ProductQueryInterface $searchQuery
     */
    public function __construct(
        ProductQueryInterface $searchQuery
    ) {
        $this->searchQuery = $searchQuery;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field       $field,
                    $context,
        ResolveInfo $info,
        array       $value = null,
        array       $args = null
    ) {
        $this->validateInput($args);

        $args['filter']['category_id'] = ['eq' => $args['category_id']];
        $args['sort']['bestsellers']   = "DESC";

        $searchResult = $this->searchQuery->getResult($args, $info, $context);

        if ($searchResult->getCurrentPage() > $searchResult->getTotalPages() && $searchResult->getTotalCount() > 0) {
            throw new GraphQlInputException(
                __(
                    'currentPage value %1 specified is greater than the %2 page(s) available.',
                    [$searchResult->getCurrentPage(), $searchResult->getTotalPages()]
                )
            );
        }

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items'       => $searchResult->getProductsSearchResult(),
            'page_info'   => [
                'page_size'    => $searchResult->getPageSize(),
                'current_page' => $searchResult->getCurrentPage(),
                'total_pages'  => $searchResult->getTotalPages()
            ],
        ];
    }

    /**
     * Validate input arguments
     *
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    private function validateInput(array $args)
    {
        if ($args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }
        if ($args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
        if (!isset($args['category_id']) || empty($args['category_id'])) {
            throw new GraphQlInputException(__('category_id value should be specified.'));
        }
    }
}
