<?php


namespace ChiakiApi\ApiBase\Consoles;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Config extends Command
{

    protected function configure()
    {
        $this->setName('ChiakiApi:config');
        $this->setDescription('Show Config');

        parent::configure();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = \Magento\Framework\App\ObjectManager::getInstance()->create('ChiakiApi\ApiBase\Model\IzRetailConfig');
        $config = $config->get();

        $output->writeln(json_encode($config));
    }
}
