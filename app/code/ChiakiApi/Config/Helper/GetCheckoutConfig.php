<?php


namespace ChiakiApi\Config\Helper;


class GetCheckoutConfig
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getCheckoutConfig($keys = ['checkout/options/guest_checkout'])
    {

        $data = [];
        foreach ($keys as $key) {
            $data[$key] = $this->scopeConfig->getValue($key);
        }

        return $data;
    }
}
