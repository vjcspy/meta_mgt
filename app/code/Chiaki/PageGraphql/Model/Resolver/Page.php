<?php


namespace Chiaki\PageGraphql\Model\Resolver;


use Magento\Framework\App\ObjectManager;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewriteGraphQl\Model\Resolver\UrlRewrite\CustomUrlLocatorInterface;

class Page implements ResolverInterface
{

    /**
     * @var UrlFinderInterface
     */
    private $urlFinder;

    /**
     * @var CustomUrlLocatorInterface
     */
    private $customUrlLocator;

    /**
     * @var int
     */
    private $redirectType;

    /**
     * @param UrlFinderInterface $urlFinder
     * @param CustomUrlLocatorInterface $customUrlLocator
     */
    public function __construct(
        UrlFinderInterface $urlFinder,
        CustomUrlLocatorInterface $customUrlLocator
    )
    {
        $this->urlFinder = $urlFinder;
        $this->customUrlLocator = $customUrlLocator;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        try {
            if (!isset($args['url']) || empty(trim($args['url']))) {
                throw new GraphQlInputException(__('"url" argument should be specified and not empty'));
            }

            $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();
            $result = null;
            $url = $args['url'];
            if (substr($url, 0, 1) === '/' && $url !== '/') {
                $url = ltrim($url, '/');
            }
            $this->redirectType = 0;
            $customUrl = $this->customUrlLocator->locateUrl($url);
            $url = $customUrl ?: $url;
            $finalUrlRewrite = $this->findFinalUrl($url, $storeId);
            if ($finalUrlRewrite) {
                $relativeUrl = $finalUrlRewrite->getRequestPath();
                $resultArray = $this->rewriteCustomUrls($finalUrlRewrite, $storeId) ?? [
                        'id' => $finalUrlRewrite->getEntityId(),
                        'canonical_url' => $relativeUrl,
                        'relative_url' => $relativeUrl,
                        'redirectCode' => $this->redirectType,
                        'type' => $this->sanitizeType($finalUrlRewrite->getEntityType()),
                        'metadata' => $finalUrlRewrite->getMetadata() ? json_encode($finalUrlRewrite->getMetadata()) : null
                    ];

                if (empty($resultArray['id'])) {
                    throw new GraphQlNoSuchEntityException(
                        __('No such entity found with matching URL key: %url', ['url' => $url])
                    );
                }

                $result = $resultArray;
            }
        } catch (\Exception $exception) {
            $result = null;
        }

        $storeId = (int)$context->getExtensionAttributes()->getStore()->getId();

        if (!isset($args['userId']) || empty(trim($args['userId']))) {
            throw new GraphQlInputException(__('"userId" argument should be specified and not empty'));
        }

        return $this->mergeWithChiakiPageData($args['url'], $args['userId'], $storeId, $result);
    }

    /**
     * Handle custom urls with and without redirects
     *
     * @param UrlRewrite $finalUrlRewrite
     * @param int $storeId
     * @return array|null
     */
    private function rewriteCustomUrls(UrlRewrite $finalUrlRewrite, int $storeId): ?array
    {
        if ($finalUrlRewrite->getEntityType() === 'custom' || !($finalUrlRewrite->getEntityId() > 0)) {
            $finalCustomUrlRewrite = clone $finalUrlRewrite;
            $finalUrlRewrite = $this->findFinalUrl($finalCustomUrlRewrite->getTargetPath(), $storeId, true);
            $relativeUrl =
                $finalCustomUrlRewrite->getRedirectType() == 0
                    ? $finalCustomUrlRewrite->getRequestPath() : $finalUrlRewrite->getRequestPath();
            return [
                'id' => $finalUrlRewrite->getEntityId(),
                'canonical_url' => $relativeUrl,
                'relative_url' => $relativeUrl,
                'redirectCode' => $finalCustomUrlRewrite->getRedirectType(),
                'type' => $this->sanitizeType($finalUrlRewrite->getEntityType())
            ];
        }
        return null;
    }

    /**
     * Find the final url passing through all redirects if any
     *
     * @param string $requestPath
     * @param int $storeId
     * @param bool $findCustom
     * @return UrlRewrite|null
     */
    private function findFinalUrl(string $requestPath, int $storeId, bool $findCustom = false): ?UrlRewrite
    {
        $urlRewrite = $this->findUrlFromRequestPath($requestPath, $storeId);
        if ($urlRewrite) {
            $this->redirectType = $urlRewrite->getRedirectType();
            while ($urlRewrite && $urlRewrite->getRedirectType() > 0) {
                $urlRewrite = $this->findUrlFromRequestPath($urlRewrite->getTargetPath(), $storeId);
            }
        } else {
            $urlRewrite = $this->findUrlFromTargetPath($requestPath, $storeId);
        }
        if ($urlRewrite && ($findCustom && !$urlRewrite->getEntityId() && !$urlRewrite->getIsAutogenerated())) {
            $urlRewrite = $this->findUrlFromTargetPath($urlRewrite->getTargetPath(), $storeId);
        }

        return $urlRewrite;
    }

    /**
     * Find a url from a request url on the current store
     *
     * @param string $requestPath
     * @param int $storeId
     * @return UrlRewrite|null
     */
    private function findUrlFromRequestPath(string $requestPath, int $storeId): ?UrlRewrite
    {
        return $this->urlFinder->findOneByData(
            [
                'request_path' => $requestPath,
                'store_id' => $storeId
            ]
        );
    }

    /**
     * Find a url from a target url on the current store
     *
     * @param string $targetPath
     * @param int $storeId
     * @return UrlRewrite|null
     */
    private function findUrlFromTargetPath(string $targetPath, int $storeId): ?UrlRewrite
    {
        return $this->urlFinder->findOneByData(
            [
                'target_path' => $targetPath,
                'store_id' => $storeId
            ]
        );
    }

    /**
     * Sanitize the type to fit schema specifications
     *
     * @param string $type
     * @return string
     */
    private function sanitizeType(string $type): string
    {
        return strtoupper(str_replace('-', '_', $type));
    }

    /**
     * @param $url
     * @param $userId
     * @param $storeId
     * @param $urlRewriteData
     * @return array|null
     */
    protected function mergeWithChiakiPageData($url, $userId, $storeId, $urlRewriteData): ?array
    {
        if (is_null($urlRewriteData)) {
            $collection = $this->getChiakiPageCollection()
                ->addFieldToFilter('type', 'CHIAKI_PAGE')
                ->addFieldToFilter('user_id', $userId)
                ->addFieldToFilter('store_id', ['in' => [
                    $storeId,
                    'default'
                ]])
                ->addFieldToFilter('url', $url)
                ->setOrder('store_id', 'ASC');

            $data = $collection->getFirstItem();

            if (!$data->getId()) {
                // Neu khong ton tai thi thu tim voi $user default. La cac page default cua he thong nhu home, checkout, login,....

                /*
                 * Chi co 3 truong hop config
                 *
                 * | user    | store   | case
                 * | default | default | default
                 * |    1    | default | custom
                 * |    1    |    1    | custom
                 * | default |    1    |   X
                 *
                 * */
                $collection = $this->getChiakiPageCollection()
                    ->addFieldToFilter('type', 'CHIAKI_PAGE')
                    ->addFieldToFilter('user_id', 'default')
                    ->addFieldToFilter('store_id', 'default')
                    ->addFieldToFilter('url', $url);

                $data = $collection->getFirstItem();
            }

            if ($data->getId()) {
                return [
                    'relative_url' => $url,
                    'type' => 'CHIAKI_PAGE',
                    'config_data' => $data->getData('config_data'),
                    'additional_data' => $data->getData('additional_data')
                ];
            }

            return null;
        } else {
            $collection = $this->getChiakiPageCollection()
                ->addFieldToFilter('type', $urlRewriteData['type'])
                ->addFieldToFilter('user_id', $userId)
                ->addFieldToFilter('store_id', ['in' => [
                    $storeId,
                    'default'
                ]])
                ->setOrder('store_id', 'ASC');

            $data = $collection->getFirstItem();

            if (!$data->getId()) {
                $collection = $this->getChiakiPageCollection()
                    ->addFieldToFilter('type', $urlRewriteData['type'])
                    ->addFieldToFilter('user_id', 'default')
                    ->addFieldToFilter('store_id', 'default');

                $data = $collection->getFirstItem();
            }
            if ($data->getId()) {
                return array_merge($urlRewriteData, [
                    'config_data' => $data->getData('config_data'),
                    'additional_data' => $data->getData('additional_data')
                ]);
            }

            return $urlRewriteData;
        }
    }


    /**
     * @return \Chiaki\Page\Model\ResourceModel\ChiakiPage\Collection
     */
    protected function getChiakiPageCollection(): \Chiaki\Page\Model\ResourceModel\ChiakiPage\Collection
    {
        return ObjectManager::getInstance()->create(\Chiaki\Page\Model\ResourceModel\ChiakiPage\Collection::class);
    }
}
