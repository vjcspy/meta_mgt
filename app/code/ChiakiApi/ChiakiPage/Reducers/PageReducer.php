<?php


namespace ChiakiApi\ChiakiPage\Reducers;


use ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface;
use ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface;
use ChiakiApi\ApiBase\Api\IzRetailReducer;
use ChiakiApi\ApiBase\Model\IzRetailApiManagement;
use Magento\Framework\App\ObjectManager;

class PageReducer extends IzRetailApiManagement implements IzRetailReducer
{

    public function reduce(IzRetailActionInterface $action, IzRetailResponseInterface $response)
    {
        switch ($action->getType()) {
            case 'save-chiaki-page':
                $data = $this->saveChiakiPage($action->getPayload());
                break;
            default:
                $data = [];
        }

        $response->setData($data);
    }

    protected function saveChiakiPage($payload)
    {
        if (!isset($payload['user_id'])) {
            throw new \Exception("Must define user_id");
        }

        if (!isset($payload['type'])) {
            throw new \Exception("Must define type");
        }

        if (!isset($payload['config_data'])) {
            throw new \Exception("Must define config_data");
        }

        $storeId = isset($payload['store_id']) ? $payload['store_id'] : 'default';


        /** @var \Chiaki\Page\Model\ChiakiPage $pageModel */
        $pageModel = ObjectManager::getInstance()->create(\Chiaki\Page\Model\ChiakiPage::class);

        $existing = $pageModel->getCollection();
        if (isset($payload['url'])) {
            $existing->addFieldToFilter('url', $payload['url']);
        }
        $existing = $existing
            ->addFieldToFilter('type', $payload['type'])
            ->addFieldToFilter('store_id', $payload['store_id'] ?? 'default')
            ->addFieldToFilter('user_id', $payload['user_id'] ?? 'default')
            ->getFirstItem();

        if ($existing->getData('chiakipage_id')) {
            $pageModel->load($existing->getData('chiakipage_id'),'chiakipage_id');
        }

        $pageModel->addData([
            'type' => $payload['type'],
            'user_id' => $payload['user_id'],
            'store_id' => $storeId,
            'url' => $payload['url'] ?? null,
            'config_data' => $payload['config_data'],
            'additional_data' => $payload['additional_data']
        ])->save();

        return [];
    }
}
