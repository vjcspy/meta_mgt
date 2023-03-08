<?php


namespace ChiakiApi\ApiBase\Model;


use Magento\Store\Model\StoreManager;

class IzRetailApiContext
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    private $request;
    /**
     * @var StoreManager
     */
    private $storeManager;

    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        StoreManager $storeManager
    )
    {
        $this->request = $request;
        $this->storeManager = $storeManager;
    }

    /**
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStore(): ?\Magento\Store\Api\Data\StoreInterface
    {
        $storeCode = $this->request->getHeader('Store');
        if(empty($storeCode)){
            $storeCode = null;
        }
        return $this->storeManager->getStore($storeCode);
    }
}
