<?php
/**
 *
 * @author Khoi Le - mr.vjcspy@gmail.com
 * @time 7/14/20 3:41 PM
 *
 */

namespace ChiakiApi\ApiBase\Helper;


class TimeHelperData
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var array
     */
    private $_storeTimezone;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezoneInterface;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface
    )
    {
        $this->storeManager = $storeManager;
        $this->timezoneInterface = $timezoneInterface;
    }

    /**
     * @param integer $storeId
     *
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getTimezoneForStore($storeId)
    {
        if (!is_numeric($storeId)) {
            $storeId = $storeId->getId();
        }
        if (!isset($this->_storeTimezone[$storeId])) {
            $storeManager = $this->storeManager->getStore($storeId);

            $this->_storeTimezone[$storeId] = $this->timezoneInterface->getConfigTimezone(
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeManager->getCode()
            );
        }

        return $this->_storeTimezone[$storeId];
    }

    /**
     * @param $time
     * @param $storeId
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function convertTimeDBUsingTimeZone($time, $storeId): string
    {
        $timeObject = new \DateTime($time);
        $timeObject->setTimezone(new \DateTimeZone($this->getTimezoneForStore($storeId)));

        return $timeObject->format(\Magento\Framework\Stdlib\DateTime::DATETIME_PHP_FORMAT);
    }
}
