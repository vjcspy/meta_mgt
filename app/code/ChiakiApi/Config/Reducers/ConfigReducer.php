<?php


namespace ChiakiApi\Config\Reducers;


use Chiaki\Config\Api\ChiakiConfigRepositoryInterface;
use ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface;
use ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface;
use ChiakiApi\ApiBase\Api\IzRetailReducer;
use ChiakiApi\ApiBase\Model\IzRetailApiManagement;
use Magento\Framework\ObjectManagerInterface;
use ChiakiApi\ApiBase\Model\Data\IzRetailResponseFactory;

class ConfigReducer extends IzRetailApiManagement implements IzRetailReducer
{

    /**
     * @var \Chiaki\Config\Model\ChiakiConfig
     */
    private $chiakiConfig;
    /**
     * @var ChiakiConfigRepositoryInterface
     */
    private $chiakiConfigRepository;
    /**
     * @var \Chiaki\Config\Helper\Data
     */
    private $chiakiHelperData;
    /**
     * @var \ChiakiApi\Config\Helper\GetCheckoutConfig
     */
    private $getCheckoutConfig;

    public function __construct(
        ObjectManagerInterface $objectManager,
        IzRetailResponseFactory $izRetailResponseFactory,
        \Chiaki\Config\Model\ChiakiConfig $chiakiConfig,
        ChiakiConfigRepositoryInterface $chiakiConfigRepository,
        \Chiaki\Config\Helper\Data $chiakiHelperData,
        \ChiakiApi\Config\Helper\GetCheckoutConfig $getCheckoutConfig
    )
    {
        parent::__construct($objectManager, $izRetailResponseFactory);
        $this->chiakiConfig = $chiakiConfig;
        $this->chiakiConfigRepository = $chiakiConfigRepository;
        $this->chiakiHelperData = $chiakiHelperData;
        $this->getCheckoutConfig = $getCheckoutConfig;
    }

    public function reduce(IzRetailActionInterface $action, IzRetailResponseInterface $response)
    {
        switch ($action->getType()) {
            case 'save-chiaki-config':
                $data = $this->saveChiakiConfig($action->getPayload());
                break;
            case 'get-checkout-config':
            default:
                $data = $this->getCheckoutConfig->getCheckoutConfig();
        }

        $response->setData($data);
    }

    protected function saveChiakiConfig($payload)
    {
        if (!isset($payload['user_id'])) {
            throw new \Exception('please define user_id');
        }

        if (!isset($payload['key'])) {
            throw new \Exception('please define key');
        }

        if (!isset($payload['value'])) {
            throw new \Exception('please define value');
        }

        if ($existedConfig = $this->chiakiHelperData->getChiakiConfig($payload['user_id'] ?? 'default', $payload['key'], $payload['store_id'] ?? 'default')) {
            $this->chiakiConfig->load($existedConfig['id'])->setData('value', $payload['value'])->save();
        } else {
            $data = $this->chiakiConfig->addData($payload)->setData('store_id', $payload['store_id'] ?? 'default')->getDataModel();
            $this->chiakiConfigRepository->save($data);
        }

        return $this->chiakiHelperData->getChiakiConfig($payload['user_id'], $payload['key'], isset($payload['store_id']) ?? 'default');
    }

}
