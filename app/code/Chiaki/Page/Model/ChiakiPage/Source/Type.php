<?php


namespace Chiaki\Page\Model\ChiakiPage\Source;


use Magento\Framework\Data\OptionSourceInterface;

class Type implements OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            ['label' => 'CHIAKI PAGE', 'value' => 'CHIAKI_PAGE'],
            ['label' => 'PRODUCT', 'value' => 'PRODUCT'],
            ['label' => 'CATEGORY', 'value' => 'CATEGORY'],
            ['label' => 'CMS_PAGE', 'value' => 'CMS_PAGE'],
        ];
    }

    /**
     * Retrieve options for edit form
     *
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'CHIAKI_PAGE' => 'CHIAKI PAGE',
            'PRODUCT' => 'PRODUCT',
            'CATEGORY' => 'CATEGORY',
            'CMS_PAGE' => 'CMS_PAGE'
        ];
    }
}
