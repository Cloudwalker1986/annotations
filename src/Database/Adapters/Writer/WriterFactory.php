<?php
declare(strict_types=1);


namespace Database\Adapters\Writer;


use Autowired\Autowired;
use Autowired\DependencyContainer;
use Database\Adapters\AdapterConfig;
use Database\Adapters\SupportedAdapters;
use RuntimeException;

class WriterFactory
{
    #[Autowired]
    private AdapterConfig $adapterConfig;

    public static function getWriterAdapter(): WriterAdapterInterface
    {
        /** @var WriterFactory $factory */
        $factory = DependencyContainer::getInstance()->get(self::class);

        $adapter = null;

        $adapterConfig = $factory->adapterConfig;
        $container = DependencyContainer::getInstance();

        if (SupportedAdapters::isPdo($adapterConfig)) {
            $adapter = $container->get(PdoWriterAdapter::class);
        } elseif (SupportedAdapters::isMysqli($adapterConfig)) {
            $adapter = $container->get(MysqliWriterAdapter::class);
        }

        if (!($adapter instanceof WriterAdapterInterface)) {
            throw new RuntimeException(
                'Adapter type ' . $adapterConfig->getAdapterType() . ' is not supported yet. Currently we support only ' . SupportedAdapters::getAdapterTypes()
            );

        }
        return $adapter;
    }
}
