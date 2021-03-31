<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Provider;

use Oro\Bundle\PaymentBundle\Method\Provider\AbstractPaymentMethodProvider;
use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\Provider\PledgConfigProviderInterface;
use Pledg\Bundle\PaymentBundle\PaymentMethod\Factory\PledgPaymentMethodFactoryInterface;

class PledgMethodProvider extends AbstractPaymentMethodProvider
{
    /**
     * @var PledgPaymentMethodFactoryInterface
     */
    protected $factory;

    /**
     * @var PledgConfigProviderInterface
     */
    private $configProvider;

    /**
     * @param PledgConfigProviderInterface $configProvider
     * @param PledgPaymentMethodFactoryInterface $factory
     */
    public function __construct(
        PledgConfigProviderInterface $configProvider,
        PledgPaymentMethodFactoryInterface $factory
    ) {
        parent::__construct();

        $this->configProvider = $configProvider;
        $this->factory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    protected function collectMethods()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addPledgMethod($config);
        }
    }

    /**
     * @param PledgConfigInterface $config
     */
    protected function addPledgMethod(PledgConfigInterface $config)
    {
        $this->addMethod(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
