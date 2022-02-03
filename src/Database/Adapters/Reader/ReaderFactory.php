<?php
declare(strict_types=1);

namespace Database\Adapters\Reader;

use Autowired\Autowired;
use Autowired\DependencyContainer;
use Database\Adapters\AdapterConfig;
use Database\Adapters\SupportedAdapters;
use RuntimeException;

class ReaderFactory
{
    #[Autowired]
    private AdapterConfig $adapterConfig;

    public static function getReaderAdapter(): ReaderAdapterInterface
    {
        /** @var ReaderFactory $factory */
        $factory = DependencyContainer::getInstance()->get(self::class);

        $adapter = null;

        $adapterConfig = $factory->adapterConfig;
        $container = DependencyContainer::getInstance();

        if (SupportedAdapters::isPdo($adapterConfig)) {
            $adapter = $container->get(PdoReaderAdapter::class);
        }

        if (!($adapter instanceof ReaderAdapterInterface)) {
            throw new RuntimeException(
                'Adapter type ' . $adapterConfig->getAdapterType() . ' is not supported yet. Currently we support only ' . SupportedAdapters::getAdapterTypes()
            );

        }
        return $adapter;

    }
}
