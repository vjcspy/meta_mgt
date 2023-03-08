<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Chiaki\Catalog\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class Period implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'daily', 'label' => __('Daily')], ['value' => 'month', 'label' => __('Month')],  ['value' => 'year', 'label' => __('Year')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['daily' => __('Daily'), 'month' => __('Month'), 'year' => __('Year')];
    }
}
