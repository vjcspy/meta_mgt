<?php


namespace ChiakiApi\ApiBase\Model;


use ChiakiApi\ApiContract\Model\DataObject;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Webapi\Exception as WebapiException;

class IzRetailApiManagement
{
    public static $FROM_RETAIL_API = false;

    const VALIDATE_CONDITION_TYPE = [
        'eq' => 'equalValue',
        'neq' => 'notEqualValue',
        'like' => 'likeValue',
        'nlike' => 'notLikeValue',
        'is' => 'isValue',
        'in' => 'inValues',
        'nin' => 'notInValues',
        'notnull' => 'valueIsNotNull',
        'null' => 'valueIsNull',
        'moreq' => 'moreOrEqualValue',
        'gt' => 'greaterValue',
        'lt' => 'lessValue',
        'gteq' => 'greaterOrEqualValue',
        'lteq' => 'lessOrEqualValue',
        'finset' => 'valueInSet',
        'from' => 'fromValue',
        'to' => 'toValue'
    ];

    /**
     * @var \Magento\Framework\Api\SearchCriteria
     */
    private $_searchCriteria;

    /**
     * @var ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * @var \ChiakiApi\ApiBase\Model\Data\IzRetailResponseFactory
     */
    protected $responseFactory;

    protected $storeId = null;

    private $_apiContext = null;

    public function __construct(
        ObjectManagerInterface $objectManager,
        \ChiakiApi\ApiBase\Model\Data\IzRetailResponseFactory $izRetailResponseFactory
    )
    {
        $this->objectManager = $objectManager;
        $this->responseFactory = $izRetailResponseFactory;
    }

    /**
     * @param array $payload
     * @return \Magento\Framework\Api\SearchCriteria
     * @throws WebapiException
     */
    protected function getSearchCriteria($payload = [])
    {
        if ($this->_searchCriteria === null) {

            /** @var \Magento\Framework\Api\SearchCriteria $searchCriteria */
            $searchCriteria = $this->objectManager->create(SearchCriteriaInterface::class);

            $this->_processStore($payload);

            $searchCriteria->setData('store_id', $this->storeId);

            if (!is_array($payload)) {
                throw new WebapiException(__('please define payload data'));
            }

            if (!isset($payload['page_size'])) {
                $payload['page_size'] = 100;
            }
            $searchCriteria->setPageSize($payload['page_size']);
            if (!isset($payload['current_page'])) {
                $payload['current_page'] = 1;
            }
            $searchCriteria->setCurrentPage($payload['current_page']);


            /*
             * Làm  việc với kiểu filter cũ. Nếu nằm trong  cùng 1 filters thì các điều kiện là OR
             * */
            if (isset($payload['filters']) && is_array($payload['filters'])) {
                $filterGroup = $this->getFilterGroup();
                $groups = [];
                $filters = [];
                foreach ($payload['filters'] as $filterData) {
                    $this->_validateFilterData($filterData);

                    /** @var \Magento\Framework\Api\Filter $filter */
                    $filter = $this->objectManager->create(\Magento\Framework\Api\Filter::class);

                    $filter->setField($filterData['field'])
                        ->setValue($filterData['value']);

                    if (isset($filterData['condition_type'])) {
                        $filter->setConditionType($filterData['condition_type']);
                    }

                    $filters[] = $filter;
                }

                $filterGroup->setFilters($filters);
                $groups[] = $filterGroup;
                $searchCriteria->setFilterGroups($groups);
            }

            if (isset($payload['filterGroups']) && is_array($payload['filterGroups'])) {
                $groups = [];
                foreach ($payload['filterGroups'] as $group) {
                    if (!is_array($group)) {
                        throw new WebapiException(__('filter group data must be array'));
                    }

                    $filterGroup = $this->getFilterGroup();
                    $filters = [];

                    foreach ($group as $filterData) {
                        $this->_validateFilterData($filterData);

                        /** @var \Magento\Framework\Api\Filter $filter */
                        $filter = $this->objectManager->create(\Magento\Framework\Api\Filter::class);

                        $filter->setField($filterData['field'])
                            ->setValue($filterData['value']);

                        if (isset($filterData['condition_type'])) {
                            $filter->setConditionType($filterData['condition_type']);
                        }

                        $filters[] = $filter;
                    }
                    $filterGroup->setFilters($filters);
                    $groups[] = $filterGroup;
                }

                $searchCriteria->setFilterGroups($groups);
            }

            return $this->_searchCriteria = $searchCriteria;
        }
        return $this->_searchCriteria;
    }

    /**
     * @param $payload
     * @return void
     */
    protected function _processStore($payload)
    {
        if (isset($payload['store_id']) && is_numeric($payload['store_id'])) {
            /** @var \Magento\Store\Model\StoreManagerInterface $storeManager */
            $storeManager = $this->objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);


            $storeManager->setCurrentStore($payload['store_id']);

            $this->storeId = $payload['store_id'];
        }
    }

    /**
     * @param $filterData
     * @throws WebapiException
     */
    protected function _validateFilterData($filterData)
    {
        if (!isset($filterData['field'])) {
            throw new WebapiException(__('filter must have `field` property'));
        }

        if (!isset($filterData['value'])) {
            throw new WebapiException(__('filter must have `value` property'));
        }

        if (isset($filterData['condition_type'])) {
            if (!array_key_exists($filterData['condition_type'], self::VALIDATE_CONDITION_TYPE)) {
                throw new WebapiException(__('condition type invalid. We only support: ' . json_encode(self::VALIDATE_CONDITION_TYPE)));
            }
        }
    }

    /**
     * @return \ChiakiApi\ApiBase\Model\Data\IzRetailResponse
     */
    protected function getResponse()
    {
        return $this->responseFactory->create();
    }

    protected function transformSearchResultToResponse(\Magento\Framework\Api\SearchResultsInterface $searchResults): Data\IzRetailResponse
    {
        $response = $this->getResponse();
        $response->setData([
            'rows' => array_values(array_map(function ($item) {
                return $item->getData();
            }, $searchResults->getItems())),
            'search_criteria' => $this->_searchCriteria->__toArray(),
            'total_count' => $searchResults->getTotalCount()
        ]);
        return $response;
    }

    /**
     * @return \Magento\Framework\Api\Search\FilterGroup
     */
    protected function getFilterGroup(): \Magento\Framework\Api\Search\FilterGroup
    {
        return $this->objectManager->create(\Magento\Framework\Api\Search\FilterGroup::class);
    }

    /**
     * @return \Magento\Framework\Api\Filter
     */
    protected function getFilter(): \Magento\Framework\Api\Filter
    {
        return $this->objectManager->create(\Magento\Framework\Api\Filter::class);
    }

    protected function getContext(): \ChiakiApi\ApiBase\Model\IzRetailApiContext
    {
        if ($this->_apiContext === null) {
            $this->_apiContext = $this->objectManager->create(\ChiakiApi\ApiBase\Model\IzRetailApiContext::class);
        }

        return $this->_apiContext;
    }

    /**
     * @return \Magento\Framework\App\RequestInterface
     */
    protected function getRequest(): \Magento\Framework\App\RequestInterface
    {
        return $this->objectManager->get(\Magento\Framework\App\RequestInterface::class);
    }
}
