<?php

/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chiaki\Catalog\Console\Command;

use Chiaki\Catalog\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResizeImage extends Command
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Data
     */
    private $helper;

    public function __construct(
        CollectionFactory $collectionFactory,
        Data $helper,
        string $name = null
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->helper            = $helper;
        parent::__construct($name);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $collection = $this->collectionFactory->create()->addMediaGalleryData();
        /** @var Product $product */
        foreach ($collection as $product) {
            $gallery = $product->getMediaGalleryImages();
            foreach ($gallery as $item) {
                if ($item->getMediaType() == 'image') {
                    $imagePath = $item->getData('file');
                    $result    = $this->detectImage($imagePath);
                    if ($result) {
                        $output->writeln($imagePath . " resize success.");
                    } else {
                        $output->writeln($imagePath . " resize fail.");
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("chiaki:generate:resize");
        $this->setDescription("Generate small product image use on graphql");
        parent::configure();
    }

    /**
     * Detect image
     *
     * @param string|null $imagePath
     *
     * @return bool|string
     */
    private function detectImage(?string $imagePath)
    {
        $newWidth  = $this->helper->getWidth();
        $newHeight = $this->helper->getHeight();
        return $this->helper->getResizeImage($imagePath, $newWidth, $newHeight, true);
    }
}

