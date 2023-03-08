<?php
declare(strict_types=1);

namespace Chiaki\Catalog\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{

    /**
     * @var AdapterFactory
     */
    protected $imageFactory;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected $storeManager;

    protected $_directory;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        AdapterFactory $imageFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->imageFactory        = $imageFactory;
        $this->filesystem          = $filesystem;
        $this->storeManager        = $storeManager;
        $this->_directory          = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        parent::__construct($context);
    }

    /**
     * @param $imageName string imagename only(abc.jpg)
     * @param $width     int
     * @param $height    int
     *
     * @return string
     */
    public function getResizeImage($imageName, $width = 0, $height = 350, $isReturnBool = false)
    {
        /* Real path of image from directory */
        $realPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('catalog/product/' . $imageName);
        if (!$this->_directory->isFile($realPath) || !$this->_directory->isExist($realPath)) {
            return false;
        }
        list($originalWidth, $originalHeight) = getimagesize($realPath);
        list($width, $height) = $this->calculateSize($originalWidth, $originalHeight, $width, $height);
        if (!$isReturnBool) {
            $resizedPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('catalog/product/resized/' . $width . 'x' . $height . $imageName);
            if ($this->_directory->isFile($resizedPath) && $this->_directory->isExist($resizedPath)) {
                return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/resized/' . $width . 'x' . $height . $imageName;
            }
        }
        $imagePrePath = explode('/', $imageName);
        array_pop($imagePrePath);
        $imagePrePath = implode('/', $imagePrePath);
        /* Target directory path where our resized image will be save */
        $targetDir     = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('catalog/product/resized/' . $width . 'x' . $height . $imagePrePath);
        $pathTargetDir = $this->_directory->getRelativePath($targetDir);
        /* If Directory not available, create it */
        if (!$this->_directory->isExist($pathTargetDir)) {
            $this->_directory->create($pathTargetDir);
        }
        if (!$this->_directory->isExist($pathTargetDir)) {
            return false;
        }

        $image = $this->imageFactory->create();
        $image->open($realPath);
        $image->keepAspectRatio(true);
        $image->resize($width, $height);
        $dest = $targetDir . '/' . pathinfo($realPath, PATHINFO_BASENAME);
        $image->save($dest);
        if ($this->_directory->isFile($this->_directory->getRelativePath($dest))) {
            if (!$isReturnBool) {
                return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/resized/' . $width . 'x' . $height . $imageName;
            }

            return true;
        }
        return false;
    }

    /**
     * Public method to calculate sizes
     *
     * @return array
     */
    public function calculateSize($originalW, $originalH, $maxW = 0, $maxH = 0)
    {
        if (!$maxW && !$maxH) {
            return [$originalW, $originalH];
        } elseif (!$maxW) {
            $maxW = ($maxH * $originalW) / $originalH;
        } elseif (!$maxH) {
            $maxH = ($maxW * $originalH) / $originalW;
        }

        //NOTE: to do not stretch small images
        if (($originalW < $maxW) && ($originalH < $maxH)) {
            return [$originalW, $originalH];
        }

        $sizeDepends        = $originalW / $originalH;
        $placeHolderDepends = $maxW / $maxH;
        if ($sizeDepends > $placeHolderDepends) {
            $newW = $maxW;
            $newH = $originalH * ($maxW / $originalW);
        } else {
            $newW = $originalW * ($maxH / $originalH);
            $newH = $maxH;
        }
        return [round($newW), round($newH)];
    }

    public function isEnableResize()
    {
        return $this->scopeConfig->getValue('catalog/product_graphql_image/enable_resize', ScopeInterface::SCOPE_STORE);
    }

    public function getWidth()
    {
        return $this->scopeConfig->getValue('catalog/product_graphql_image/small_image_width', ScopeInterface::SCOPE_STORE) ?? 0;
    }

    public function getHeight()
    {
        return $this->scopeConfig->getValue('catalog/product_graphql_image/small_image_height', ScopeInterface::SCOPE_STORE) ?? 350;
    }
}
