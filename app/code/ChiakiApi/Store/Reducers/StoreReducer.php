<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace ChiakiApi\Store\Reducers;

use ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface;
use ChiakiApi\ApiBase\Api\Data\IzRetailResponseInterface;
use ChiakiApi\ApiBase\Api\IzRetailReducer;
use ChiakiApi\ApiBase\Model\Data\IzRetailResponseFactory;
use ChiakiApi\ApiBase\Model\IzRetailApiManagement;
use ChiakiApi\ApiContract\Data\IzStore;
use Magento\Framework\ObjectManagerInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use ReflectionException;

class StoreReducer extends IzRetailApiManagement implements IzRetailReducer
{
    /**
     * @var \Magento\Store\Api\StoreRepositoryInterface
     */
    protected $storeRepository;
    /**
     * @var \Magento\Framework\Locale\Format
     */
    private $localeFormat;
    /**
     * @var \VNG\Storelocator\Model\StoreFactory
     */
    private $storeLocatorModelFactory;
    /**
     * @var \VNG\Storelocator\Helper\Image
     */
    private $imageHelper;
    /**
     * @var WebsiteRepositoryInterface
     */
    private $websiteRepository;

    /**
     * IzRetailStoreManagement constructor.
     * @param ObjectManagerInterface $objectManager
     * @param IzRetailResponseFactory $izRetailResponseFactory
     */
    public function __construct(
        ObjectManagerInterface                      $objectManager,
        IzRetailResponseFactory                     $izRetailResponseFactory,
        \Magento\Store\Api\StoreRepositoryInterface $storeRepository,
        WebsiteRepositoryInterface                  $websiteRepository,
        \Magento\Framework\Locale\Format            $format
    )
    {
        parent::__construct($objectManager, $izRetailResponseFactory);
        $this->storeRepository = $storeRepository;
        $this->localeFormat = $format;
        $this->websiteRepository = $websiteRepository;
    }

    /**
     * @inheritDoc
     * @throws ReflectionException
     */
    public function reduce(IzRetailActionInterface $action, IzRetailResponseInterface $response)
    {
        switch ($action->getType()) {
            case 'get-store':
            case 'get-stores':
                $data = $this->getStore($action->getPayload(), $response);
                break;
            case 'get-websites':
                $data = $this->getWebsite($action->getPayload(), $response);
                break;
            default:
                $data = [];
        }

        $response->setData($data);
    }

    protected function getWebsite($payload)
    {
        $websites = $this->websiteRepository->getList();

        $websites = array_filter($websites, function ($w) use ($payload) {
            return $w->getId() != 0 && (!isset($payload['website_ids']) || !is_array($payload['website_ids']) || in_array($w->getId(), $payload['website_ids']));
        });

        return [
            'rows' => array_values(array_map(function ($website) {
                $groups = $website->getGroups();
                $groupsData = [];

                foreach ($groups as $group) {
                    /** @var \Magento\Store\Model\Group $group */
                    $stores = $group->getStores();
                    $storeData = [];
                    foreach ($stores as $store) {
                        $izStore = (new IzStore($store->getData()));

                        $baseCurrency = $store->getBaseCurrency();
                        $izStore->setData('base_currency', $baseCurrency->getData());

                        $currentCurrency = $this->getCurrentCurrencyBaseOnStore($store);
                        $izStore->setData('current_currency', ['currency_code' => $currentCurrency]);

                        $rate = $baseCurrency->getRate($currentCurrency);
                        $izStore->setData('rate', $rate);


                        $izStore->setData('price_format', $this->localeFormat->getPriceFormat(null, $currentCurrency));

                        $storeData[] = $izStore->getOutput();
                    }

                    $groupsData[] = [
                        'id' => $group->getId(),
                        'default_store_id' => $group->getDefaultStoreId(),
                        'name' => $group->getName(),
                        'stores' => $storeData
                    ];
                }

                return [
                    'id' => $website->getId(),
                    'code' => $website->getCode(),
                    'name' => $website->getName(),
                    'default_group_id' => $website->getDefaultGroupId(),
                    'is_default' => $website->getData('is_default') == 1,
                    'groups' => $groupsData
                ];
            }, $websites)),
            'total_count' => count($websites)
        ];
    }

    /**
     * @param $payload
     * @param $response
     * @return mixed
     * @throws ReflectionException
     */
    public function getStore($payload)
    {
        $stores = $this->storeRepository->getList();

        // Store api only support store_id filter
        if (isset($payload['store_id'])) {
            $stores = array_filter($stores, function ($item) use ($payload) {
                return $item->getId() == $payload['store_id'];
            });
        }

        return [
            'rows' => array_values(array_map(function ($store) {
                $izStore = (new IzStore($store->getData()));

                $baseCurrency = $store->getBaseCurrency();
                $izStore->setData('base_currency', $baseCurrency->getData());

                $currentCurrency = $this->getCurrentCurrencyBaseOnStore($store);
                $izStore->setData('current_currency', ['currency_code' => $currentCurrency]);

                $rate = $baseCurrency->getRate($currentCurrency);
                $izStore->setData('rate', $rate);


                $izStore->setData('price_format', $this->localeFormat->getPriceFormat(null, $currentCurrency));
                return $izStore->getOutput();
            }, $stores)),
            'total_count' => count($stores)
        ];
    }

    /**
     * @param \Magento\Store\Model\Store $store
     *
     * @return mixed|string
     */
    protected function getCurrentCurrencyBaseOnStore(\Magento\Store\Model\Store $store)
    {
        // try to get currently set code among allowed
        $code = $store->getDefaultCurrencyCode();
        if (in_array($code, $store->getAvailableCurrencyCodes(true), true)) {
            return $code;
        }

        // take first one of allowed codes
        $codes = array_values($store->getAvailableCurrencyCodes(true));
        if (empty($codes)) {
            // return default code, if no codes specified at all
            return $store->getDefaultCurrencyCode();
        }

        return array_shift($codes);
    }
}

