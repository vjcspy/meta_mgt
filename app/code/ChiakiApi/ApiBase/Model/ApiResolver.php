<?php


namespace ChiakiApi\ApiBase\Model;


use ChiakiApi\ApiBase\Api\Data\IzRetailActionInterface;
use ChiakiApi\ApiBase\Api\IzRetailApiResolverInterface;
use ChiakiApi\ApiBase\Api\IzRetailReducer;
use Magento\Framework\Webapi\Exception as WebAPIException;

class ApiResolver extends IzRetailApiManagement implements IzRetailApiResolverInterface
{
    protected $_retailConfig = null;

    public function dispatch(IzRetailActionInterface $action)
    {
        $response = $this->getResponse();

        $reducer = $this->getReducerClass($action->getType());
        if ($reducer) {
            $data = $reducer->reduce($action, $response);

            if (is_array($data)) {
                $response->setData($data);
            }
        } else {
            throw new WebAPIException(__('Could not found reducer'));
        }

        return $response;
    }

    /**
     * @param $type
     * @return false| \ChiakiApi\ApiBase\Api\IzRetailReducer
     * @throws WebAPIException
     */
    protected function getReducerClass($type)
    {
        if ($this->_retailConfig === null) {
            $config = \Magento\Framework\App\ObjectManager::getInstance()->get('ChiakiApi\ApiBase\Model\IzRetailConfig');
            $this->_retailConfig = $config->get();
            if (!isset($this->_retailConfig['actions'])) {
                throw new WebAPIException("Could not found reducer config");
            } else {
                $this->_retailConfig = $this->_retailConfig['actions'];
            }
        }

        foreach ($this->_retailConfig as $item) {
            if ($type === $item['type']) {
                $instance = \Magento\Framework\App\ObjectManager::getInstance()->create($item['class']);
                if (!!$instance && $instance instanceof IzRetailReducer) {
                    return $instance;
                } else {
                    throw new WebAPIException(__('Could not resolve reducer for type ' . $type));
                }
            }
        }

        return false;
    }
}
