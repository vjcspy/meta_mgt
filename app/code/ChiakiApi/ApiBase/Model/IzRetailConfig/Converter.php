<?php


namespace ChiakiApi\ApiBase\Model\IzRetailConfig;


class Converter implements \Magento\Framework\Config\ConverterInterface
{
    public function convert($source)
    {
        $actions = $source->getElementsByTagName('actions');
        $actionsDetails = [];
        $iterator = 0;

        /** @var \DOMElement $discount */
        foreach ($actions as $action) {
            /** @var \DOMText $discountInfo */
            foreach ($action->childNodes as $actionInfo) {
                $actionData = [];
                foreach ($actionInfo->childNodes as $item) {
                    if ($item->nodeName && $item->nodeName !== "#text") {
                        $actionData[$item->nodeName] = $item->textContent;
                    }
                }
                if(!empty($actionData)){
                    $actionData['type'] = $actionInfo->getAttribute('type');
                    $actionsDetails[$actionInfo->getAttribute('type')] =  $actionData;
                }
            }
            $iterator++;
        }

        return ['actions' => $actionsDetails];
    }
}
